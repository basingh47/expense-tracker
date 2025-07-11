<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expense Report</title>
    <style>
        
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Expense Report</h2>

    @if($request->from || $request->to || $request->category)
        <p>
            <strong>Filters:</strong>
            @if($request->from) From {{ \Carbon\Carbon::parse($request->from)->format('d/m/Y') }} @endif
            @if($request->to) To {{ \Carbon\Carbon::parse($request->to)->format('d/m/Y') }} @endif
            @if($request->category) | Category: {{ $request->category }} @endif
        </p>
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

    <p><strong>Total Spent:</strong> ₹{{ number_format($totalAmount, 2) }}</p>
</body>
</html>
