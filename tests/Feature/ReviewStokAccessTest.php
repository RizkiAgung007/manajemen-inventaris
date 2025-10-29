<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewStokAccessTest extends TestCase
{
    use RefreshDatabase;

    // ===== TEST AKSES =====
    private function createSuperadmin(): User
    {
        return User::factory()->create(['role' => 'superadmin']);
    }

    public function test_guests_cannot_access_review_index(): void
    {
        $response = $this->get(route('reviews.index'));
        $response->assertRedirect('/login');
    }

    public function test_regular_users_cannot_access_review_index(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get(route('reviews.index'));
        $response->assertForbidden();
    }

    public function test_superadmin_can_access_review_index(): void
    {
        $superadmin = $this->createSuperadmin();
        $response = $this->actingAs($superadmin)->get(route('reviews.index'));
        $response->assertOk();
    }

    public function test_superadmin_can_access_review_show(): void
    {
        $superadmin = $this->createSuperadmin();
        $stockOpname = StockOpname::factory()->create();
        $response = $this->actingAs($superadmin)->get(route('reviews.show', $stockOpname));
        $response->assertOk();
    }

    // ===== TEST APPROVE =====
    public function test_superadmin_can_approve_pending_stock_opname_and_adjust_stock_correctly(): void
    {
        $admin = $this->createSuperadmin();
        $productIncrease = Product::factory()->create(['stock' => 10]);
        $productDecrease = Product::factory()->create(['stock' => 20]);
        $productSame = Product::factory()->create(['stock' => 5]);

        $stockOpname = StockOpname::factory()->recycle($admin)->create();

        // Buat detail-detail opname
        StockOpnameDetail::factory()->recycle($stockOpname)->recycle($productIncrease)->create([
            'product_id'        => $productIncrease->id,
            'system_stock'      => 10,
            'physical_stock'    => 15,
        ]);
        StockOpnameDetail::factory()->recycle($stockOpname)->recycle($productDecrease)->create([
            'product_id'        => $productDecrease->id,
            'system_stock'      => 20,
            'physical_stock'    => 18,
        ]);
        StockOpnameDetail::factory()->recycle($stockOpname)->recycle($productSame)->create([
            'product_id'        => $productSame->id,
            'system_stock'      => 5,
            'physical_stock'    => 5,
        ]);

        // Catat stok awal
        $initialStockIncrease = $productIncrease->stock;
        $initialStockDecrease = $productDecrease->stock;
        $initialStockSame = $productSame->stock;

        // Lakukan request approve
        $response = $this->actingAs($admin)->post(route('reviews.approve', $stockOpname));

        $response->assertRedirect(route('reviews.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('stock_opnames', [
            'id' => $stockOpname->id,
            'status' => 'approved',
        ]);

        $this->assertEquals($initialStockIncrease + 5, $productIncrease->refresh()->stock, "Stok produk {$productIncrease->id} seharusnya naik jadi 15");
        $this->assertEquals($initialStockDecrease - 2, $productDecrease->refresh()->stock, "Stok produk {$productDecrease->id} seharusnya turun jadi 18");
        $this->assertEquals($initialStockSame, $productSame->refresh()->stock, "Stok produk {$productSame->id} seharusnya tetap 5");

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $productIncrease->id,
            'user_id' => $admin->id,
            'quantity' => 5,
            'type' => 'stok_opname',
            'notes' => 'Dari Laporan Stok Opname #' . $stockOpname->id,
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $productDecrease->id,
            'user_id' => $admin->id,
            'quantity' => -2,
            'type' => 'stok_opname',
            'notes' => 'Dari Laporan Stok Opname #' . $stockOpname->id,
        ]);

        $this->assertDatabaseMissing('stock_movements', [
            'product_id' => $productSame->id,
            'type' => 'stok_opname',
        ]);
    }

    // ===== TEST REJECT =====
    public function test_superadmin_can_reject_pending_opname_and_stock_does_not_change(): void
    {
        $admin = $this->createSuperadmin();
        $product = Product::factory()->create(['stock' => 10]);

        $stockOpname = StockOpname::factory()->recycle($admin)->create();
        StockOpnameDetail::factory()->recycle($stockOpname)->recycle($product)->create([
            'system_stock' => 10,
            'physical_stock' => 15,
        ]);

        $initialStock = $product->stock;
        $initialMovementCount = $product->stockMovements()->count();

        // Lakukan request reject
        $response = $this->actingAs($admin)->post(route('reviews.reject', $stockOpname));

        $response->assertRedirect(route('reviews.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('stock_opnames', ['id' => $stockOpname->id, 'status' => 'rejected']);

        $this->assertEquals($initialStock, $product->refresh()->stock);

        $this->assertEquals($initialMovementCount, $product->stockMovements()->count());
    }

    // ===== TEST ALREADY PROCESSED =====
    public function test_cannot_approve_already_approved_opname(): void
    {
        $admin = $this->createSuperadmin();
        $stockOpname = StockOpname::factory()->recycle($admin)->approved()->create();
        $response = $this->actingAs($admin)->post(route('reviews.approve', $stockOpname));
        $response->assertRedirect(route('reviews.show', $stockOpname));
        $response->assertSessionHas('error');
    }

    public function test_cannot_reject_already_approved_opname(): void
    {
        $admin = $this->createSuperadmin();
        $stockOpname = StockOpname::factory()->recycle($admin)->approved()->create();
        $response = $this->actingAs($admin)->post(route('reviews.reject', $stockOpname));
        $response->assertRedirect(route('reviews.show', $stockOpname));
        $response->assertSessionHas('error');
    }

     public function test_cannot_approve_already_rejected_opname(): void
    {
        $admin = $this->createSuperadmin();
        $stockOpname = StockOpname::factory()->recycle($admin)->rejected()->create();
        $response = $this->actingAs($admin)->post(route('reviews.approve', $stockOpname));
        $response->assertRedirect(route('reviews.show', $stockOpname));
        $response->assertSessionHas('error');
    }
}
