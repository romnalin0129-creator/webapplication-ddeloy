# Kawaii Corner

เว็บแอปร้านค้าสินค้าดิจิทัลธีม LINE ภาษาไทย พัฒนาด้วย Laravel 9, PHP, MySQL, Blade, CSS และ JavaScript ในโทนชมพูพาสเทล ม่วงลาเวนเดอร์ และครีม

## ความสามารถหลัก

- หน้าแรก `/` และ `/home` แสดงสินค้า 18 รายการ แบ่งเป็นเมโลดี้ ธีม และสติกเกอร์ LINE อย่างละ 6 รายการ
- ค้นหาชื่อสินค้า กรองหมวดหมู่/สินค้าขายดี และเรียงตามราคา/ชื่อได้ พร้อมรองรับหน้าจอมือถือ
- ตะกร้าสินค้าปรับจำนวนได้และเก็บรายการไว้ใน browser; checkout ตรวจสอบสินค้าและราคาซ้ำฝั่งเซิร์ฟเวอร์
- ระบบสมาชิก Laravel Jetstream/Fortify: สมัครสมาชิก เข้าสู่ระบบ ออกจากระบบ และรีเซ็ตรหัสผ่าน
- ลูกค้าต้องเข้าสู่ระบบก่อนสั่งซื้อ ดูประวัติและรายละเอียดได้เฉพาะคำสั่งซื้อของตนเอง
- หน้ารายละเอียดคำสั่งซื้อแสดงยอดที่ต้องชำระและปุ่มแจ้งชำระเงิน โดยไม่มีรูป QR; ลูกค้าชำระผ่านช่องทางที่ร้านแจ้งแล้วกดแจ้งให้ตรวจสอบ
- ผู้ดูแลตรวจสอบยอดเงินจริงและยืนยันสถานะ “ชำระแล้ว” ในหน้า `/admin/orders`; ระบบไม่เชื่อมต่อ payment gateway และไม่ตรวจสอบธุรกรรมอัตโนมัติ
- คำสั่งซื้อบันทึก snapshot ชื่อและราคาสินค้า พร้อมเลขอ้างอิง สถานะชำระเงิน และวันเวลาที่ร้านยืนยัน
- ผู้ดูแลจัดการสินค้าแบบ CRUD ที่ `/admin/products` และตรวจสอบ/ปรับสถานะคำสั่งซื้อที่ `/admin/orders`; ป้องกันการเริ่มจัดเตรียมหรือทำคำสั่งซื้อสำเร็จก่อนยืนยันรับชำระ
- Seed สินค้าตัวอย่างผ่าน `StoreCatalogSeeder`; ข้อมูลภาพในตัวอย่างใช้อีโมจิ/กราฟิก CSS ไม่ใช่ไฟล์สินค้าลิขสิทธิ์
- มีระบบจองบริการนวดเดิมที่ `/treatments`, `/booking` และหน้าจัดการเดิมสำหรับการทดสอบโปรเจกต์

## ติดตั้งด้วย Windows / Laragon

ต้องมี PHP 8+, Composer, Node.js/npm และ MySQL

1. เปิด MySQL ใน Laragon และสร้างฐานข้อมูล `kawaii_corner` (collation `utf8mb4_unicode_ci`)
2. เปิด PowerShell ในโฟลเดอร์โปรเจกต์:

   ```powershell
   cd C:\laragon\www\webappplication
   composer install
   Copy-Item .env.example .env
   ```

3. แก้ `.env` โดยกำหนดค่าฐานข้อมูลในเครื่อง:

   ```dotenv
   APP_NAME="Kawaii Corner"
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kawaii_corner
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

   เข้า `http://127.0.0.1:8000` เพื่อเปิดหน้าร้าน เลือกสมัครสมาชิก/เข้าสู่ระบบเพื่อสั่งซื้อ ส่วน Forgot Password ใช้ Laravel Password Broker ตามปกติ

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

   ออกด้วย `exit` แล้วเพิ่มข้อมูลตัวอย่าง:

   ```powershell
   php artisan db:seed
   ```

   เข้าสู่ระบบบัญชี Admin แล้วเปิด `/admin/products` เพื่อเพิ่ม/ดู/แก้ไข/ลบสินค้า หรือ `/admin/orders` เพื่อปรับสถานะคำสั่งซื้อ การตรวจสิทธิ์ Admin ทำฝั่งเซิร์ฟเวอร์ผ่าน middleware

## Forgot Password

- สำหรับทดลองในเครื่อง กำหนด `MAIL_MAILER=log` และ `MAIL_FROM_ADDRESS=noreply@kawaiicorner.local` ใน `.env` แล้วส่งคำขอจากหน้า “ลืมรหัสผ่าน” จากนั้นตรวจลิงก์ใน `storage/logs/laravel.log` (ไม่ได้ส่งอีเมลจริง). หากแก้การตั้งค่าอีเมลแล้ว config ถูก cache ให้รัน `php artisan config:clear`.
- หากต้องการส่งอีเมล ให้กำหนด `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS` และ `MAIL_FROM_NAME` ตามผู้ให้บริการ ห้ามเก็บรหัสผ่าน SMTP ใน Git

## ทดสอบ

ชุด Feature test ใช้ฐานข้อมูลแยกที่ระบุใน `phpunit.xml` ห้ามเปลี่ยนให้ชี้ฐานข้อมูลใช้งานจริง จาก PowerShell:

```powershell
php artisan test
```

ทดสอบครอบคลุมหน้าร้าน, Auth/Password reset, การสร้างคำสั่งซื้อ, การแยกประวัติตามสมาชิก และ Admin CRUD สินค้า/สถานะคำสั่งซื้อ

## GitHub

1. สร้าง repository ว่างชื่อ `kawaii-corner` บน GitHub
2. ตรวจสอบ `.gitignore` ก่อน `git add`; ไฟล์ `.env`, `vendor`, `node_modules`, log และ cache ถูกยกเว้น
3. ใช้ PowerShell จากโฟลเดอร์โปรเจกต์นี้ (มี Git repository และ commit เริ่มต้นไว้แล้ว) เปลี่ยน `<บัญชี-GitHub>` ให้เป็นบัญชีของตน:

   ```powershell
   cd C:\laragon\www\webappplication
   git add .
   git status
   git branch -M main
   git remote add origin https://github.com/<บัญชี-GitHub>/kawaii-corner.git
   git push -u origin main
   ```

   หากเคยตั้ง remote `origin` แล้ว ให้ใช้ `git remote set-url origin https://github.com/<บัญชี-GitHub>/kawaii-corner.git` แทน `git remote add`

4. ตรวจหน้า repository ว่ามี `README.md`, `composer.json`, `composer.lock`, `routes`, `app`, `database/migrations`, `database/seeders` และ `resources/views` ครบ และไม่มี `.env`
5. แนะนำให้แยก commit งานต่อยอด เช่น `Add Kawaii Corner storefront`, `Add digital product checkout`, `Add admin catalog management`

ใน workspace นี้ไม่ได้สร้าง GitHub repository หรือ push โค้ด เพราะยังไม่มี URL/บัญชี GitHub ที่ผู้ใช้ระบุ
#   w e b a p p l i c a t i o n  
 