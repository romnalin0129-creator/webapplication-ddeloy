<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class Editor extends Component
{
    public function render()
    {
        $users = User::all();
        return view('livewire.editor', compact('users'));
    }
}
