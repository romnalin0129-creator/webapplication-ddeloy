# Relax Massage & Spa

เว็บไซต์ภาษาไทยสำหรับแนะนำบริการนวดและจองคิวออนไลน์ พัฒนาด้วย Laravel 9, PHP, MySQL, Blade, CSS และ JavaScript ในโทนครีม น้ำตาล และทองอ่อน

## ความสามารถหลัก

- หน้าแรกแสดงบริการจากฐานข้อมูล พร้อมข้อมูลราคา ระยะเวลา รายละเอียด และรูปภาพ
- ระบบสมาชิก Laravel Jetstream/Fortify: สมัครสมาชิก เข้าสู่ระบบ ออกจากระบบ และรีเซ็ตรหัสผ่าน
- ลูกค้าต้องเข้าสู่ระบบก่อนจองคิว และเห็น/ยกเลิกได้เฉพาะการจองของตนเองที่ยังรอยืนยัน
- เวลาให้จอง 09:00, 10:00, 11:00 และ 13:00–17:00 น. ตรวจสอบรอบว่างตามวันและระยะเวลาบริการ
- ป้องกันคิวซ้ำและเวลานวดทับซ้อนด้วย server-side validation, transaction, date lock และ unique slot key ใน MySQL
- บันทึก snapshot ราคาและระยะเวลาขณะจอง พร้อมรหัสจองและสถานะ `pending`, `confirmed`, `cancelled`, `completed`
- Admin Dashboard แสดงจำนวนบริการ คิววันนี้ คิวรอยืนยัน ลูกค้าสมาชิก และกราฟสรุป พร้อมค้นหา/กรองสถานะ
- Admin จัดการบริการนวดแบบ CRUD ในหน้า `/services` หน้าเดียว: เพิ่มบริการ ดูรายการ แก้ไขจากแถวบริการ และลบ พร้อมอัปโหลดรูป/กำหนดราคา/เวลา
- หน้าแรกค้นหารอบว่างแบบเรียลไทม์ตามบริการและวันที่ โดยเชื่อมกับระบบตรวจเวลาทับซ้อน และเลือกเวลาเพื่อไปต่อแบบฟอร์มจองได้
- หน้าแรกมีขั้นตอนจอง 3 ขั้น FAQ แบบเปิด-ปิด และแอนิเมชันเลื่อนปรากฏที่รองรับการตั้งค่าลดการเคลื่อนไหว
- Seed ข้อมูล 5 บริการตามโจทย์: นวดแผนไทย, นวดน้ำมันอโรม่า, นวดฝ่าเท้า, นวดคอบ่าไหล่, นวดประคบสมุนไพร

## ติดตั้งด้วย Windows / Laragon

ต้องมี PHP 8+, Composer, Node.js/npm และ MySQL

1. เปิด MySQL ใน Laragon และสร้างฐานข้อมูล `relax_massage` (collation `utf8mb4_unicode_ci`)
2. เปิด PowerShell ในโฟลเดอร์โปรเจกต์:

   ```powershell
   cd C:\laragon\www\webappplication
   composer install
   Copy-Item .env.example .env
   ```

3. แก้ `.env` โดยกำหนดค่าฐานข้อมูลในเครื่อง:

   ```dotenv
   APP_NAME="Relax Massage & Spa"
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=relax_massage
   DB_USERNAME=root
   DB_PASSWORD=
   MAIL_MAILER=log
   ```

   ใส่รหัส MySQL จริงใน `.env` หากมี ห้าม commit `.env` หรือข้อมูลรหัสผ่าน

4. สร้าง application key และตาราง:

   ```powershell
   php artisan key:generate
   php artisan migrate
   php artisan storage:link
   ```

   migration สำหรับการเปลี่ยนจาก SnapRent จะย้ายตารางจองกล้องเดิมเป็น `camera_rental_bookings` เพื่อเก็บข้อมูลไว้ แล้วสร้างตารางจองคิวนวด `bookings` และ `services` ใหม่ ไม่ลบประวัติการจองกล้องเก่า

5. ติดตั้ง/สร้าง asset ของ Laravel และเปิดเซิร์ฟเวอร์:

   ```powershell
   npm install
   npm run development
   php artisan serve
   ```

   เข้า `http://127.0.0.1:8000` สมัครบัญชีลูกค้าได้ทันที และ Forgot Password ใช้ Laravel Password Broker ตามปกติ

6. ตั้งบัญชีผู้ดูแล:

   ```powershell
   php artisan tinker
   ```

   ภายใน Tinker ระบุอีเมลบัญชีที่สมัครแล้ว:

   ```php
   $user = App\Models\User::where('email', 'admin@example.com')->firstOrFail();
   $user->is_admin = true;
   $user->save();
   ```

   ออกด้วย `exit` แล้วเพิ่มบริการตัวอย่าง:

   ```powershell
   php artisan db:seed
   ```

   เข้าสู่ระบบบัญชี Admin แล้วเปิด `/dashboard` เพื่อดูชื่อและจัดการคิวลูกค้า หรือ `/services` เพื่อเพิ่ม/ดู/แก้ไข/ลบบริการได้ในหน้าเดียว การตรวจสิทธิ์ Admin ทำฝั่งเซิร์ฟเวอร์ผ่าน middleware

## Forgot Password

- สำหรับทดลองในเครื่อง กำหนด `MAIL_MAILER=log` ใน `.env` และส่งคำขอจากหน้า “ลืมรหัสผ่าน” จากนั้นตรวจลิงก์ใน `storage/logs/laravel.log` (ไม่ได้ส่งอีเมลจริง)
- หากต้องการส่งอีเมล ให้กำหนด `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS` และ `MAIL_FROM_NAME` ตามผู้ให้บริการ ห้ามเก็บรหัสผ่าน SMTP ใน Git

## ทดสอบ

ชุด Feature test ใช้ฐานข้อมูลแยกที่ระบุใน `phpunit.xml` ห้ามเปลี่ยนให้ชี้ฐานข้อมูลใช้งานจริง จาก PowerShell:

```powershell
php artisan test
```

ทดสอบครอบคลุมหน้าแรก, Auth/Password reset, จองซ้ำและเวลาทับซ้อน, สิทธิ์ลูกค้า, แก้ไข/ยกเลิกการจอง, Admin CRUD/สถานะ และข้อมูล seed

## GitHub

1. สร้าง repository ว่างชื่อ `relax-massage-spa` บน GitHub
2. ตรวจสอบ `.gitignore` ก่อน `git add`; ไฟล์ `.env`, `vendor`, `node_modules`, log และ cache ถูกยกเว้น
3. ใช้ PowerShell จากโฟลเดอร์โปรเจกต์ แล้วเปลี่ยน `<บัญชี-GitHub>` ให้เป็นบัญชีของตน:

   ```powershell
   cd C:\laragon\www\webappplication
   git init
   git add .
   git status
   git commit -m "Build Relax Massage booking application"
   git branch -M main
   git remote add origin https://github.com/<บัญชี-GitHub>/relax-massage-spa.git
   git push -u origin main
   ```

4. ตรวจหน้า repository ว่ามี `README.md`, `composer.json`, `composer.lock`, `routes`, `app`, `database/migrations`, `database/seeders` และ `resources/views` ครบ และไม่มี `.env`
5. แนะนำให้แยก commit งานต่อยอด เช่น `Add Relax Massage storefront`, `Add appointment booking and slot validation`, `Add service CRUD and admin dashboard`

ใน workspace นี้ไม่ได้สร้าง GitHub repository หรือ push โค้ด เพราะยังไม่มี URL/บัญชี GitHub ที่ผู้ใช้ระบุ
