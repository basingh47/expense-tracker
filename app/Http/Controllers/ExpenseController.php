<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use PDF;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::where('user_id', auth()->id()); // optional if multi-user


        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }


        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        // Apply "category" filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $totalAmount = (clone $query)->sum('amount');

        // Get filtered & paginated results
        $expenses = $query->orderBy('date', 'desc')->paginate(3)->withQueryString();
        // dd($expenses->toArray());
        return view('expenses.index', compact('expenses', 'totalAmount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|in:Food,Transport,Bills,Health,Others',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        // Save the data to the database
        Expense::create([
            'user_id' => auth()->id(), // if your expenses are user-specific
            'date' => $validated['date'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
        ]);

        // Redirect with success message
        return redirect()->route('expenses.index')->with('success', 'Expense added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403); // unauthorized access
        }

        // Validate the input
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|in:Food,Transport,Bills,Health,Others',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        // Update the expense
        $expense->update($validated);

        // Redirect with success message
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403); // Unauthorized
        }

        // Delete the expense
        $expense->delete();

        // Redirect back with a success message
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }


    /**
     * Pdf Generate based on request
     */

    public function exportPdf(Request $request)
    {
        $query = Expense::where('user_id', auth()->id());

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->orderBy('date', 'desc')->get();
        $totalAmount = $expenses->sum('amount');

        $pdf = PDF::loadView('expenses.pdf', compact('expenses', 'totalAmount', 'request'));
        $pdf->setOptions(['defaultFont' => 'DejaVu Sans']);
        // return $pdf->download('expenses_report.pdf');
        return $pdf->stream('expenses_report.pdf');

    }
}
