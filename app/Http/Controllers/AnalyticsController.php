<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Guardian;
use App\Models\Review;
use App\Models\TuitionContract;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class AnalyticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => User::count(),
            'total_tutors'    => Tutor::count(),
            'total_guardians' => Guardian::count(),
            'total_contracts' => TuitionContract::count(),
            'active_contracts'=> TuitionContract::where('status', 'active')->count(),
            'total_reviews'   => Review::count(),
            'avg_rating'      => round(Review::avg('rating') ?? 0, 2),
            'total_complaints'=> Complaint::count(),
            'open_complaints' => Complaint::where('status', 'Open')->count(),
        ];

        return view('admin.analytics', compact('stats'));
    }

    public function exportCsv()
    {
        $stats = [
            ['Metric', 'Value'],
            ['Total Users',        User::count()],
            ['Total Tutors',       Tutor::count()],
            ['Total Guardians',    Guardian::count()],
            ['Total Contracts',    TuitionContract::count()],
            ['Active Contracts',   TuitionContract::where('status', 'active')->count()],
            ['Total Reviews',      Review::count()],
            ['Average Rating',     round(Review::avg('rating') ?? 0, 2)],
            ['Total Complaints',   Complaint::count()],
            ['Open Complaints',    Complaint::where('status', 'Open')->count()],
        ];

        $csv = implode("\n", array_map(fn($row) => implode(',', $row), $stats));

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="analytics_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
