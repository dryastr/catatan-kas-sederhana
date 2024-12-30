<?php

namespace App\Http\Controllers\admin;

use App\Exports\CashsOutExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashOut;
use App\Models\Cash;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;

class CashsOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashOuts = CashOut::all();
        $totalBalance = Cash::sum('amount') - CashOut::sum('amount');
        $totalBalanceCashOut = CashOut::sum('amount');
        $categories = Category::all();
        return view('admin.cashs-out.index', compact('cashOuts', 'totalBalance', 'categories', 'totalBalanceCashOut'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        CashOut::create($request->all());

        return redirect()->route('cashs-out.index')->with('success', 'Cash out entry created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'amount' => 'required|numeric',
        ]);

        $cash = CashOut::findOrFail($id);
        $cash->update($request->all());

        return redirect()->route('cashs-out.index')->with('success', 'Cash out entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cash = CashOut::findOrFail($id);
        $cash->delete();

        return redirect()->route('cashs-out.index')->with('success', 'Cash out entry deleted successfully.');
    }

    public function export()
    {
        return Excel::download(new CashsOutExport, 'cashout_entries.xlsx');
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->input('month');

        if ($month) {
            $cashOuts = CashOut::with('category')
                ->whereMonth('date', '=', \Carbon\Carbon::parse($month)->month)
                ->whereYear('date', '=', \Carbon\Carbon::parse($month)->year)
                ->get();

            $totalAmount = $cashOuts->sum('amount');
        } else {
            $cashOuts = collect();
            $totalAmount = 0;
        }

        return view('admin.cashs-out.monthly_report', compact('cashOuts', 'month', 'totalAmount'));
    }
}
