<?php

namespace App\Exports;

use App\Models\ShippingCode;
use App\Models\LevelPart;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ShippingDataExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $shippingId;

    public function __construct($shippingId)
    {
        $this->shippingId = $shippingId;
    }

    public function query()
    {
        // Eager load relationships for efficiency and build linear join query
        return LevelPart::query()
            ->select('level_parts.*')
            ->join('level_cases', 'level_parts.level_case_id', '=', 'level_cases.id')
            ->join('containers', 'level_cases.container_id', '=', 'containers.id')
            ->where('containers.shipping_code_id', $this->shippingId)
            ->with(['levelCase.container.shippingCode']);
    }

    public function headings(): array
    {
        // Matching EXACTLY user provided reference image headers
        return [
            'Shipping Code',
            'Model',
            'O/F',
            'Lot No.',
            'Case No.',
            'Parts No.',
            'RUIBE',
            'Parts Name',
            'QTY',
            'Unit Weight',
            'Net Weight',
            'FTA Code',
            'Container No.'
        ];
    }

    public function map($part): array
    {
        $levelCase = $part->levelCase;
        $container = $levelCase ? $levelCase->container : null;
        $shipping = $container ? $container->shippingCode : null;

        return [
            $shipping ? $shipping->code : '',
            $levelCase ? $levelCase->model : '',
            $levelCase ? $levelCase->o_f : '',
            $levelCase ? $levelCase->lot_no : '',
            $levelCase ? $levelCase->case_no : '',
            $part->parts_no,
            $part->ruibe,
            $part->parts_name,
            $part->qty,
            number_format($part->unit_weight, 6, '.', ''),
            number_format($part->net_weight, 6, '.', ''),
            $part->fta_code,
            $container ? $container->container_no : ''
        ];
    }
}
