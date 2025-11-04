<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
#[Title('Beranda - SPPD')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }
}
