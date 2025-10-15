<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use Livewire\Attributes\Layout;

class ReportesTest extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.reportes.reportes-test');
    }
}