<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CashsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cash;
use App\Models\CashOut;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;

class CashsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cashs = Cash::with('category')->get();
        $categories = Category::all();
        $totalBalance = Cash::sum('amount') - CashOut::sum('amount');
        $totalBalanceCashIn = CashOut::sum('amount');
        return view('admin.cashs.index', compact('cashs', 'categories', 'totalBalance', 'totalBalanceCashIn'));
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
            'amount' => 'required|numeric',
        ]);

        Cash::create($request->all());

        return redirect()->route('cashs.index')->with('success', 'Cash entry created successfully.');
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

        $cash = Cash::findOrFail($id);
        $cash->update($request->all());

        return redirect()->route('cashs.index')->with('success', 'Cash entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cash = Cash::findOrFail($id);
        $cash->delete();

        return redirect()->route('cashs.index')->with('success', 'Cash entry deleted successfully.');
    }

    public function export()
    {
        return Excel::download(new CashsExport, 'cash_entries.xlsx');
    }
}
