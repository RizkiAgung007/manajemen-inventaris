<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $colorClasses;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $status)
    {
        $this->colorClasses = match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'stok_awal' => 'bg-blue-100 text-blue-800',
            'penyesuaian_manual' => 'bg-purple-100 text-purple-800',
            'stok_opname' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'penjualan' => 'bg-cyan-100 text-cyan-800',
            'barang_rusak' => 'bg-orange-100 text-orange-800',
            'pemakaian_internal' => 'bg-pink-100 text-pink-800',
            'pembatalan' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
