<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminHeader extends Component
{
    public $showProfileMenu = false;

    public function toggleProfileMenu()
    {
        $this->showProfileMenu = !$this->showProfileMenu;
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.layouts.admin-header');
    }
}
