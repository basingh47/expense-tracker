@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Dashboard</h3>
            </div>
            <div class="card-body">
                <div class="container mt-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Total Spent Today</h5>
                                    <h3>₹{{ number_format($totalToday, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5>Total Spent This Month</h5>
                                            <h3>₹{{ number_format($totalMonth, 2) }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <h5 class="card-header">Monthly Spending Chart</h5>
                                        <div class="card-body">
                                            <canvas id="monthlyChart" height="100"></canvas>
                                        </div>
                                        {{-- <h5 class="mt-4">Monthly Spending Chart</h5> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <h5 class="card-header">Top Categories</h5>
                        <div class="card-body">
                            <ul class="list-group mb-4">
                                @foreach($topCategories as $cat)
                                    <li class="list-group-item d-flex justify-content-between">
                                        {{ $cat->category }}
                                        <span>₹{{ number_format($cat->total, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <h5 class="card-header">Recent Expenses</h5>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentExpenses as $expense)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                            <td>{{ $expense->category }}</td>
                                            <td>₹{{ number_format($expense->amount, 2) }}</td>
                                            <td>{{ $expense->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [{
                    label: '₹ Spent Per Day',
                    data: {!! json_encode($totals) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return '₹' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

@endsection