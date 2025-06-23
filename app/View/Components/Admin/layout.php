<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{
    public function render(): View|Closure|string
    {
        return view('backend.admin.layouts.app');
    }
}
