<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User; 

class Usertable extends Component
{
    public function delete(int $userid)
    {
        //dd($userid);
        User::where('id', $userid)->delete();
       
    }
    public function render()
    {
        $users = User::query()
            ->get(); 

        //dd($users);

        return view('livewire.usertable', compact('users'));
    }
}
