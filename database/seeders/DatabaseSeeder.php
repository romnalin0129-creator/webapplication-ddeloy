<?php

namespace Database\Seeders;

use App\Models\MassageService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'นวดแผนไทย',
                'description' => 'ศาสตร์การนวดดั้งเดิมที่ช่วยคลายเส้น ยืดเหยียดร่างกาย และคืนความสมดุลให้ทุกการเคลื่อนไหว',
                'duration_minutes' => 60,
                'price' => 300,
                'image_path' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1000&q=85',
            ],
            [
                'name' => 'นวดน้ำมันอโรม่า',
                'description' => 'สัมผัสการนวดผ่อนคลายด้วยน้ำมันหอมระเหยกลิ่นละมุน ช่วยปลดปล่อยความเหนื่อยล้าทั้งกายและใจ',
                'duration_minutes' => 60,
                'price' => 500,
                'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1000&q=85',
            ],
            [
                'name' => 'นวดฝ่าเท้า',
                'description' => 'ผ่อนคลายกล้ามเนื้อเท้าและกระตุ้นจุดสะท้อนทั่วร่างกาย เหมาะสำหรับวันที่เดินทางหรือยืนมานาน',
                'duration_minutes' => 60,
                'price' => 250,
                'image_path' => 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1000&q=85',
            ],
            [
                'name' => 'นวดคอบ่าไหล่',
                'description' => 'คลายความตึงล้าจากการทำงานและการใช้หน้าจอ ด้วยแรงกดที่พอดีและใส่ใจเฉพาะจุด',
                'duration_minutes' => 30,
                'price' => 200,
                'image_path' => 'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?auto=format&fit=crop&w=1000&q=85',
            ],
            [
                'name' => 'นวดประคบสมุนไพร',
                'description' => 'ประคบลูกประคบสมุนไพรอุ่น ๆ เพื่อคลายกล้ามเนื้อ พร้อมกลิ่นหอมจากสมุนไพรไทยธรรมชาติ',
                'duration_minutes' => 90,
                'price' => 600,
                'image_path' => 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=1000&q=85',
            ],
        ];

        foreach ($services as $service) {
            MassageService::updateOrCreate(['name' => $service['name']], $service + ['is_active' => true]);
        }
    }
}
