@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card">
            <h5 class="card-header">Expense List</h5>

            <div class="card-body">
                <div class="container">
                    <form method="GET" class="row g-3 mb-4">
                        @csrf
                        <div class="col-md-2">
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}"
                                placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}"
                                placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach(['Food', 'Transport', 'Bills', 'Health', 'Shopping', 'Education', 'Travel', 'Entertainment', 'Recharge', 'Groceries', 'Rent', 'Utilities', 'Others']
 as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('expenses.index') }}" class="btn btn-primary w-100">Clear</a>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('expenses.pdf', request()->query()) }}" class="btn btn-primary w-100">PDF</a>
                        </div>

                        <div class="col-md-1">
                            <a href="{{ route('expenses.send', request()->query()) }}"
                                class="btn btn-success w-100">Email</a>
                        </div>
                    </form>

                    <a href="{{ route('expenses.create') }}" class="btn btn-success mb-3">+ Add Expense</a>

                    @if(request('from') || request('to') || request('category'))
                        <div
                            class="alert alert-info alert-dismissible fade show d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Filter Applied:</strong>
                                @if(request('from'))
                                    From <strong>{{ \Carbon\Carbon::parse(request('from'))->format('d/m/Y') }}</strong>
                                @endif
                                @if(request('to'))
                                    To <strong>{{ \Carbon\Carbon::parse(request('to'))->format('d/m/Y') }}</strong>
                                @endif
                                @if(request('category'))
                                    | Category: <strong>{{ request('category') }}</strong>
                                @endif
                            </div>
                            <div>
                                <strong>Total Spent:</strong> ₹{{ number_format($totalAmount, 2) }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Amount (₹)</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>₹{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->description }}</td>
                                    <td>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No expenses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection