<?php

namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\User;

class Addusertable extends Component
{
    // ประกาศตัวแปรสำหรับเก็บค่าที่ผู้ใช้กรอก
    public $name,
           $email,
           $password;
    
    public function adduser()
    {
        // โค้ดสำหรับเพิ่มผู้ใช้
        //dd($this->name);
        // คุณสามารถใช้ User::create() หรือวิธีอื่น ๆ เพื่อบันทึกข้อมูล

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password), // เข้ารหัสรหัสผ่าน
        ]);
        return redirect()->to(route('users'));
    }
    public function render()
    {
        return view('livewire.addusertable');
    }
}