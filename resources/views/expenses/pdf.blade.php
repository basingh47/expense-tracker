<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Expense Report</title>
    <style>
        @page {
            margin: 100px 50px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            position: relative;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            border-bottom: 1px solid #000;
        }

        header img {
            height: 40px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            border-top: 1px solid #000;
            font-size: 10px;
        }

        .report-title {
            margin-top: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .filters {
            margin-top: 15px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .total {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <!-- Left: Logo -->
            <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('assets/images/logo.png'))) }}"
                style="height: 40px;">

            <!-- Right: Report Date -->
            <div style="font-size: 12px; font-weight: bold;">
                Date: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
            </div>
        </div>
    </header>


    <footer>
        &copy; {{ date('Y') }} Expense Tracker Report
        </script>
    </footer>


    <div class="report-title">Expense Report</div>


    @if($request->from || $request->to || $request->category)
        <div class="filters">
            <strong>Filters:</strong>
            @if($request->from) From {{ \Carbon\Carbon::parse($request->from)->format('d/m/Y') }} @endif
            @if($request->to) To {{ \Carbon\Carbon::parse($request->to)->format('d/m/Y') }} @endif
            @if($request->category) | Category: {{ $request->category }} @endif
        </div>
    @endif


    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount (₹)</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>₹{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Spent: ₹{{ number_format($totalAmount, 2) }}
    </div>

</body>

</html>