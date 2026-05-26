<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    /**
     * Index -Laporan User
     */
    public function index(Request $request)
    {
        $filter = $request->filter ?? 'all';
        
        $query = User::withCount('orders')->withSum('orders', 'total_price');
        
        switch ($filter) {
            case 'today':
                $query->whereHas('orders', function($q) {
                    $q->whereDate('created_at', today());
                });
                break;
            case 'week':
                $query->whereHas('orders', function($q) {
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                });
                break;
            case 'month':
                $query->whereHas('orders', function($q) {
                    $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                });
                break;
        }
        
        $users = $query->orderBy('orders_count', 'desc')->get();
        
        $totalUser = $users->count();
        $totalOrder = $users->sum('orders_count');
        $totalBelanja = $users->sum('orders_sum_total_price');
        
        return view('laporan.user.index', compact('users', 'filter', 'totalUser', 'totalOrder', 'totalBelanja'));
    }

    /**
     * Export
     */
    public function export(Request $request)
    {
        $users = User::withCount('orders')->withSum('orders', 'total_price')->get();
        
        $filename = 'laporan-user-' . now()->format('Y-m-d') . '.csv';
        
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="' . $filename . '"'];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Email', 'Total Order', 'Total Belanja', 'Terakhir Belanja']);
            
            foreach ($users as $index => $user) {
                $lastOrder = $user->orders()->latest('created_at')->first();
                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    $user->email,
                    $user->orders_count,
                    number_format($user->orders_sum_total_price ?? 0, 0, ',', '.'),
                    $lastOrder ? $lastOrder->created_at->format('d/m/Y') : '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}