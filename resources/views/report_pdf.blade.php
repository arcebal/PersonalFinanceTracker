<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finance Report</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 32px;
            color: #12233d;
            background: #f4f8fc;
        }

        .page {
            background: #ffffff;
            border: 1px solid #dce7f3;
            border-radius: 24px;
            padding: 28px;
        }

        .header {
            padding: 22px 24px;
            border-radius: 20px;
            background: linear-gradient(135deg, #0a63d8, #6cd4ff);
            color: #f8fbff;
            margin-bottom: 24px;
        }

        .eyebrow {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.8;
        }

        h1 {
            margin: 10px 0 6px;
            font-size: 28px;
        }

        .header p {
            margin: 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .section {
            margin-top: 24px;
            padding: 18px 20px;
            border: 1px solid #dce7f3;
            border-radius: 18px;
            background: #fbfdff;
        }

        .section-title {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: bold;
            color: #12233d;
        }

        .section-subtitle {
            margin: 0 0 16px;
            font-size: 12px;
            color: #5d7089;
        }

        .chart-wrap {
            text-align: center;
            padding: 12px 0 4px;
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 14px;
            border: 1px solid #dce7f3;
            background: #ffffff;
        }

        .no-data {
            margin: 0;
            font-size: 13px;
            color: #5d7089;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th {
            padding: 10px 12px;
            background: #eef5fc;
            color: #5d7089;
            font-size: 11px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            text-align: left;
        }

        td {
            padding: 10px 12px;
            border-top: 1px solid #e6edf7;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="eyebrow">TrackerYarn Report</div>
            <h1>Finance summary</h1>
            <p>Generated on {{ now()->format('F j, Y g:i A') }}</p>
        </div>

        <div class="section">
            <div class="section-title">Expense breakdown</div>
            <div class="section-subtitle">Category-level view of recorded expense activity.</div>

            @if(!empty($pieImage))
                <div class="chart-wrap">
                    <img src="{{ $pieImage }}" style="max-width:420px;" alt="Expense breakdown chart" />
                </div>
            @else
                <p class="no-data">No expense breakdown available.</p>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Monthly income vs expense</div>
            <div class="section-subtitle">Recent month-by-month cash flow comparison.</div>

            @if(!empty($barImage))
                <div class="chart-wrap">
                    <img src="{{ $barImage }}" style="max-width:620px;" alt="Monthly income vs expense chart" />
                </div>
            @else
                <p class="no-data">No monthly data available.</p>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Monthly summary</div>
            <div class="section-subtitle">Income and expense totals for each reported month.</div>

            @if(!empty($months))
                <table>
                    <thead>
                        <tr><th>Month</th><th>Income</th><th>Expense</th></tr>
                    </thead>
                    <tbody>
                        @foreach($months as $i => $m)
                            <tr>
                                <td>{{ $m }}</td>
                                <td>₱{{ number_format($income[$i] ?? 0, 2) }}</td>
                                <td>₱{{ number_format($expense[$i] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No monthly totals available.</p>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Budget vs actual</div>
            <div class="section-subtitle">Category budgets compared with recorded spend for the selected month.</div>

            @if(!empty($budgetLabels))
                <table>
                    <thead>
                        <tr><th>Category</th><th>Budget</th><th>Spent</th></tr>
                    </thead>
                    <tbody>
                        @foreach($budgetLabels as $i => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>₱{{ number_format($budgetAmounts[$i] ?? 0, 2) }}</td>
                                <td>₱{{ number_format($spentAmounts[$i] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No budget data available.</p>
            @endif
        </div>
    </div>
</body>
</html>
