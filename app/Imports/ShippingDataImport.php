<?php

namespace App\Imports;

use App\Models\ShippingCode;
use App\Models\Container;
use App\Models\LevelCase;
use App\Models\LevelPart;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class ShippingDataImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // 1. Scan the file for distinct shipping codes
        $extractedCodes = $rows->pluck('shipping_code')
            ->filter()
            ->map(fn($c) => trim($c))
            ->unique()
            ->toArray();

        // 2. Check if any of these already exist in our database
        if (!empty($extractedCodes)) {
            $existing = ShippingCode::whereIn('code', $extractedCodes)->pluck('code')->toArray();
            if (!empty($existing)) {
                // Stop entirely if any overlap is found to prevent partial/double importing
                throw new \Exception("Shipping Code(s) [" . implode(', ', $existing) . "] are already imported. To re-import, please delete the old data first.");
            }
        }

        DB::transaction(function () use ($rows) {
            $currentShippingCode = null;
            $currentContainer = null;
            $currentCase = null;
            $lastCaseGroupKey = null; // to track if model/lot/case combo changed

            foreach ($rows as $row) {
                // 1. Process Shipping Code column (e.g. "shipping_code")
                $shippingCodeVal = trim($row['shipping_code'] ?? '');
                if ($shippingCodeVal !== '') {
                    $currentShippingCode = ShippingCode::firstOrCreate(['code' => $shippingCodeVal]);
                }

                // If we still don't have a shipping code (e.g. empty first row data), skip
                if (!$currentShippingCode) {
                    continue;
                }

                // 2. Process Container No column (e.g. "container_no")
                $containerNoVal = trim($row['container_no'] ?? '');
                if ($containerNoVal !== '') {
                    $currentContainer = Container::firstOrCreate([
                        'shipping_code_id' => $currentShippingCode->id,
                        'container_no' => $containerNoVal
                    ], [
                        'status' => 'shipping'
                    ]);
                }

                if (!$currentContainer) {
                    continue;
                }

                // 3. Check if Case group data changes
                $modelVal = trim($row['model'] ?? '');
                $ofVal = trim($row['of'] ?? ($row['o_f'] ?? ''));
                $lotNoVal = trim($row['lot_no'] ?? '');
                $caseNoVal = trim($row['case_no'] ?? '');

                // A new case is detected if at least one case identifier is present
                if ($modelVal !== '' || $lotNoVal !== '' || $caseNoVal !== '' || $ofVal !== '') {
                    $currentCase = LevelCase::create([
                        'container_id' => $currentContainer->id,
                        'model' => $modelVal,
                        'o_f' => $ofVal,
                        'lot_no' => $lotNoVal,
                        'case_no' => $caseNoVal,
                    ]);
                }

                if (!$currentCase) {
                    continue;
                }

                // 4. Create Part if Parts No exists in this row
                $partsNo = trim($row['parts_no'] ?? '');
                if ($partsNo !== '') {
                    LevelPart::create([
                        'level_case_id' => $currentCase->id,
                        'parts_no' => $partsNo,
                        'ruibe' => $row['ruibe'] ?? null,
                        'parts_name' => $row['parts_name'] ?? null,
                        'qty' => intval($row['qty'] ?? 0),
                        'unit_weight' => floatval($row['unit_weight'] ?? 0),
                        'net_weight' => floatval($row['net_weight'] ?? 0),
                        'fta_code' => $row['fta_code'] ?? null,
                    ]);
                }
            }
        });
    }
}
