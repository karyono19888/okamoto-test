<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\ShippingCodeRepositoryInterface;
use App\Imports\ShippingDataImport;
use App\Exports\ShippingDataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Container;
use App\Models\ShippingCode;
use App\Models\LevelPart;
use App\Models\LevelCase;
use App\Traits\LogsActivity;

class ShippingController extends Controller
{
    use LogsActivity;

    protected $repo;

    public function __construct(ShippingCodeRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        $this->logActivity('View', 'Accessed list of Stored Shipments');
        $shippingCodes = $this->repo->getAllWithDetails();
        return view('shipping.index', compact('shippingCodes'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ShippingDataImport, $request->file('file'));
            $this->logActivity('Upload', 'Uploaded an Excel data file successfully.');
            return redirect()->back()->with('success', 'Data imported successfully!');
        } catch (\Exception $e) {
            $this->logActivity('Upload Attempt Failed', 'Attempted to upload file but failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function export($id)
    {
        $shipping = ShippingCode::findOrFail($id);
        $this->logActivity('Export', "Generated Excel Export for Shipment Code: {$shipping->code}");
        
        $fileName = 'export-' . $shipping->code . '-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new ShippingDataExport($id), $fileName);
    }

    public function show($id)
    {
        $shippingCode = ShippingCode::findOrFail($id);
        // Decoupled pagination for massive scale instead of eager-loading entire hierarchy
        $containers = $shippingCode->containers()->orderBy('created_at')->paginate(25);

        $this->logActivity('View Details', "Accessed container listing for Shipping Code: {$shippingCode->code}");
        return view('shipping.show', compact('shippingCode', 'containers'));
    }

    public function showContainer(Container $container)
    {
        $shippingCode = $container->shippingCode;
        $cases = $container->cases()->orderBy('created_at')->paginate(50);
        
        $this->logActivity('View Container Detail', "Drilled down into Cases for Container [{$container->container_no}]");
        return view('shipping.show_container', compact('shippingCode', 'container', 'cases'));
    }

    public function showCase(LevelCase $case)
    {
        $container = $case->container;
        $shippingCode = $container->shippingCode;
        $parts = $case->parts()->orderBy('created_at')->paginate(100);
        
        $this->logActivity('View Case Detail', "Drilled down into Parts for Case ID [{$case->id}]");
        return view('shipping.show_case', compact('shippingCode', 'container', 'case', 'parts'));
    }

    public function completeContainer(Container $container)
    {
        // Enforce business rule: cannot revert once complete
        if ($container->status === 'complete') {
            return redirect()->back()->with('error', 'Status is already complete.');
        }
        
        $container->update(['status' => 'complete']);
        $this->logActivity('Status Update', "Marked Container [{$container->container_no}] as complete.");
        return redirect()->back()->with('success', 'Container marked as complete.');
    }

    public function destroy($id)
    {
        $shippingCode = ShippingCode::findOrFail($id);
        
        if ($shippingCode->containers()->where('status', 'complete')->exists()) {
            return redirect()->route('shipping.index')->with('error', 'Cannot delete this shipment because some containers are already completed.');
        }

        $code = $shippingCode->code;
        $shippingCode->delete();
        $this->logActivity('Delete', "Deleted entire Shipping hierarchy for Code: {$code}");
        return redirect()->route('shipping.index')->with('success', 'Shipping record deleted.');
    }

    public function destroyContainer(Container $container)
    {
        if ($container->status === 'complete') {
            return redirect()->back()->with('error', 'Cannot delete a completed container.');
        }
        $shippingId = $container->shipping_code_id;
        $cNo = $container->container_no;
        $container->delete();
        $this->logActivity('Delete', "Deleted Container [{$cNo}]");
        return redirect()->route('shipping.show', $shippingId)->with('success', 'Container deleted.');
    }

    public function destroyPart(LevelPart $part)
    {
        $container = $part->levelCase->container;
        if ($container->status === 'complete') {
            return redirect()->back()->with('error', 'Cannot delete part from a completed container.');
        }
        $shippingId = $container->shipping_code_id;
        $pNo = $part->parts_no;
        $part->delete();
        $this->logActivity('Delete', "Deleted Part [{$pNo}]");
        return redirect()->route('shipping.show', $shippingId)->with('success', 'Part deleted.');
    }

    public function editPart(LevelPart $part)
    {
        if ($part->levelCase->container->status === 'complete') {
            return redirect()->back()->with('error', 'Cannot edit items in a completed container.');
        }
        $this->logActivity('View Edit Page', "Viewing the edit form for Part [{$part->parts_no}]");
        return view('shipping.edit_part', compact('part'));
    }

    public function updatePart(Request $request, LevelPart $part)
    {
        $container = $part->levelCase->container;
        if ($container->status === 'complete') {
            return redirect()->back()->with('error', 'Updates blocked: Parent container is completed.');
        }

        $data = $request->validate([
            'parts_no' => 'required',
            'parts_name' => 'nullable',
            'qty' => 'required|integer',
            'unit_weight' => 'required|numeric',
            'net_weight' => 'required|numeric',
        ]);

        $part->update($data);
        $this->logActivity('Edit', "Updated data for Part [{$part->parts_no}]");
        
        $shippingId = $part->levelCase->container->shipping_code_id;
        return redirect()->route('shipping.show', $shippingId)->with('success', 'Part updated.');
    }
}
