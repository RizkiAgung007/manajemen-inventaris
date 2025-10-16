<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoleBadge extends Component
{
    public string $colorClasses;

    public function __construct(public string $role)
    {
        $this->colorClasses = match ($role) {
            'superadmin' => 'bg-indigo-100 text-indigo-800',
            'admin' => 'bg-yellow-100 text-yellow-800',
            'user' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.role-badge');
    }
}
