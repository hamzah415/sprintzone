<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;

class UserReportController extends Controller
{
    public function index()
    {
        // Semua user yang pernah belanja
        $users = User::whereHas('orders')
            ->withCount('orders')
            ->withSum('orders', 'total_price')
            ->orderBy('orders_count', 'desc')
            ->get();
        
        $totalUser = $users->count();
        $totalBelanja = $users->sum('orders_sum_total_price');
        
        return view('laporan.user.index', compact('users', 'totalUser', 'totalBelanja'));
    }

    public function export()
    {
        $users = User::whereHas('orders')
            ->withCount('orders')
            ->withSum('orders', 'total_price')
            ->get();
        
        $filename = 'laporan-user-' . now()->format('Y-m-d') . '.csv';
        
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Email', 'Total Order', 'Total Belanja', 'Terakhir Belanja']);
            
            foreach ($users as $index => $user) {
                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    $user->email,
                    $user->orders_count,
                    number_format($user->orders_sum_total_price ?? 0, 0, ',', '.'),
                    $user->orders->max('created_at')?->format('d/m/Y') ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}