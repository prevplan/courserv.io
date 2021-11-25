<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home')
            ->layout('layouts.app', [
                'metaTitle' => 'Dashboard',
                'active' => 'home',
            ]);
    }
}
