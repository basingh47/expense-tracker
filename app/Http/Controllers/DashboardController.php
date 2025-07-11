<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $totalToday = Expense::where('user_id', $userId)->whereDate('date', $today)->sum('amount');
        $totalMonth = Expense::where('user_id', $userId)->whereBetween('date', [$monthStart, now()])->sum('amount');

        $topCategories = Expense::selectRaw('category, SUM(amount) as total')
            ->where('user_id', $userId)
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentExpenses = Expense::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();


        $monthEnd = Carbon::now()->endOfMonth();

        $dailyExpenses = Expense::selectRaw('DATE(date) as day, SUM(amount) as total')
            ->where('user_id', $userId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // dd($dailyExpenses->toArray());
        // Prepare data for chart
        $dates = [];
        $totals = [];

        $period = Carbon::parse($monthStart)->daysUntil($monthEnd);

        foreach ($period as $date) {
            $formatted = $date->format('d M');
            $dates[] = $formatted;
            // dd($date->toDateString());
            $matchingDay = $dailyExpenses->firstWhere('day', $date->toDateString());

            if ($matchingDay) {
                $dayTotal = $matchingDay->total;
            } else {
                $dayTotal = 0;
            }

            $totals[] = $dayTotal;
        }


        return view('dashboard.index', compact('totalToday', 'totalMonth', 'topCategories', 'recentExpenses', 'dates', 'totals'));
    }

}
