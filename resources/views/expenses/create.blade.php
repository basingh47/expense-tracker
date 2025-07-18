@extends('layouts.app')
@php
    $today = \Carbon\Carbon::now()->format('Y-m-d');
@endphp
@section('content')
    <div class="container mt-4">
        <div class="card">
            <h5 class="card-header">Add New Expense</h5>
            <div class="card-body">
                <div class="container">
                    <form action="{{ route('expenses.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                                value="{{ old('date') }}" max="{{ $today }}" required>
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">-- Select Category --</option>
                                @foreach(['Food', 'Transport', 'Bills', 'Health', 'Shopping', 'Education', 'Travel', 'Entertainment', 'Recharge', 'Groceries', 'Rent', 'Utilities', 'Others']
 as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Amount (₹)</label>
                            <input type="number" name="amount" step="0.01" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"
                                required>
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Description (optional)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button class="btn btn-success">Add Expense</button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection