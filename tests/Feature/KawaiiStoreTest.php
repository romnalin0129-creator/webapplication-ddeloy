<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\StoreProduct;
use App\Models\User;
use Database\Seeders\StoreCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KawaiiStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StoreCatalogSeeder::class);
    }

    public function test_homepage_lists_the_three_categories_and_all_eighteen_products(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Kawaii Corner')
            ->assertSee('เมโลดี้ไลน์')
            ->assertSee('ธีมไลน์')
            ->assertSee('สติกเกอร์ไลน์')
            ->assertSee('18 ชิ้น');

        foreach (config('store_catalog.products') as $product) {
            $response->assertSee($product['name'])
                ->assertSee($product['description']);
        }
    }

    public function test_catalog_seeder_updates_existing_products_from_config(): void
    {
        $product = StoreProduct::query()->where('slug', 'melody-cat')->firstOrFail();
        $product->update([
            'name' => 'ชื่อสินค้าเก่า',
            'description' => 'คำอธิบายเก่า',
        ]);

        $this->seed(StoreCatalogSeeder::class);

        $expected = collect(config('store_catalog.products'))->firstWhere('id', 'melody-cat');
        $this->assertDatabaseHas('store_products', [
            'slug' => 'melody-cat',
            'name' => $expected['name'],
            'description' => $expected['description'],
        ]);
    }

    public function test_checkout_requires_a_signed_in_customer(): void
    {
        $this->post(route('orders.store'), ['items' => ['melody-cat' => 1]])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_signed_in_customer_can_place_an_order_and_view_only_their_own_order(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();

        $this->actingAs($customer)
            ->post(route('orders.store'), ['items' => ['melody-cat' => 2, 'theme-sky' => 1]])
            ->assertSessionHasNoErrors();

        $orderNumber = Order::query()->where('user_id', $customer->id)->value('order_number');
        $this->assertNotNull($orderNumber);
        $this->assertSame(2, Order::query()->where('order_number', $orderNumber)->count());
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'order_number' => $orderNumber,
            'product_name' => collect(config('store_catalog.products'))->firstWhere('id', 'melody-cat')['name'],
            'quantity' => 2,
            'total' => 58,
        ]);

        Order::create([
            'user_id' => $otherCustomer->id,
            'order_number' => 'KC-PRIVATE123',
            'product_name' => 'Private order',
            'customer_name' => $otherCustomer->name,
            'unit_price' => 1,
            'quantity' => 1,
            'total' => 1,
            'status' => 'pending',
        ]);

        $this->get(route('orders.show', $orderNumber))
            ->assertOk()
            ->assertSee(collect(config('store_catalog.products'))->firstWhere('id', 'melody-cat')['name'])
            ->assertDontSee('สินค้าดิจิทัลตัวอย่างสำหรับโปรเจกต์การศึกษา')
            ->assertDontSee('Private order');

        $this->actingAs($customer)
            ->get(route('orders.show', 'KC-PRIVATE123'))
            ->assertNotFound();
    }

    public function test_checkout_rejects_unknown_products_and_quantities_above_the_limit(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('orders.store'), ['items' => ['not-a-product' => 1]])
            ->assertSessionHasErrors('items');

        $this->post(route('orders.store'), ['items' => ['melody-cat' => 11]])
            ->assertSessionHasErrors('items.melody-cat');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_admin_can_create_update_and_delete_store_products(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'slug' => 'new-kitty-theme',
                'name' => 'ธีมลูกแมวสีพีช',
                'category' => 'theme',
                'price' => 69,
                'icon' => '🐈',
                'tag' => 'มาใหม่',
                'color' => 'peach',
                'description' => 'ธีมลูกแมวสีพีชแสนน่ารัก',
                'is_active' => 1,
            ])
            ->assertSessionHasNoErrors();

        $product = StoreProduct::query()->where('slug', 'new-kitty-theme')->firstOrFail();
        $this->assertTrue($product->is_active);

        $this->put(route('admin.products.update', $product), [
            'slug' => 'new-kitty-theme',
            'name' => 'ธีมลูกแมวสีพีชใหม่',
            'category' => 'theme',
            'price' => 79,
            'icon' => '🐈',
            'tag' => 'ฮิต',
            'color' => 'peach',
            'description' => 'รายละเอียดที่อัปเดต',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('store_products', [
            'id' => $product->id,
            'name' => 'ธีมลูกแมวสีพีชใหม่',
            'price' => 79,
            'is_active' => false,
        ]);

        $this->delete(route('admin.products.destroy', $product))->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('store_products', ['id' => $product->id]);
    }

    public function test_admin_can_update_order_status_and_customers_cannot_open_admin_tools(): void
    {
        $customer = User::factory()->create();
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'KC-STATUS123',
            'product_name' => 'เมโลดี้แมวน้อยร้องเหมียว',
            'customer_name' => $customer->name,
            'unit_price' => 29,
            'quantity' => 1,
            'total' => 29,
            'status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get(route('admin.products.index'))
            ->assertRedirect(route('home'));

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->patch(route('admin.orders.update', $order->order_number), ['status' => 'processing'])
            ->assertSessionHas('error');

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->patch(route('admin.orders.update', $order->order_number), ['status' => 'completed'])
            ->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'order_number' => $order->order_number,
            'status' => 'pending',
        ]);
        $this->assertNull($order->fresh()->completed_at);
    }

    public function test_customer_can_report_payment_without_a_qr_image_and_admin_confirms_it(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $orderNumber = 'KC-PAYMENT01';
        Order::create([
            'user_id' => $customer->id,
            'order_number' => $orderNumber,
            'product_name' => 'เมโลดี้แมวน้อยร้องเหมียว',
            'customer_name' => $customer->name,
            'unit_price' => 29,
            'quantity' => 1,
            'total' => 29,
            'status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get(route('orders.show', $orderNumber))
            ->assertOk()
            ->assertSeeInOrder([$orderNumber, 'ขอบคุณที่ช็อปกับเรา!', 'การชำระเงิน'])
            ->assertSee('ยอดที่ต้องชำระ')
            ->assertSee('ยืนยันชำระ')
            ->assertDontSee('แจ้งร้านตรวจสอบ')
            ->assertDontSee('payment-qr-frame')
            ->assertDontSee('PromptPay');

        $this->post(route('orders.report-payment', $orderNumber))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'order_number' => $orderNumber,
            'payment_status' => 'reported',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.payment', $orderNumber), ['payment_status' => 'paid'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('orders', [
            'order_number' => $orderNumber,
            'payment_status' => 'paid',
        ]);
        $this->assertNotNull(Order::query()->where('order_number', $orderNumber)->firstOrFail()->paid_at);

        $this->actingAs($customer)
            ->get(route('orders.show', $orderNumber))
            ->assertOk()
            ->assertSee('ยืนยันชำระ')
            ->assertDontSee('ชำระแล้ว · แจ้งร้านตรวจสอบ');

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $orderNumber), ['status' => 'processing'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('orders', ['order_number' => $orderNumber, 'status' => 'processing']);
    }
}
