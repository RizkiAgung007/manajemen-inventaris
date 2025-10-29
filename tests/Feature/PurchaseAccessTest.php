<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PurchaseAccessTest extends TestCase
{
    use RefreshDatabase;

    // ===== TEST AKSES =====
    public function test_guests_cannot_access_purchase_orders_index(): void
    {
        $response = $this->get('/dashboard/purchase-orders');
        $response->assertRedirect('/login');
    }

    public function test_users_with_role_user_are_forbidden_from_accessing_proudcts(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/dashboard/purchase-orders');
        $response->assertForbidden();
    }

    public function test_superadmin_users_can_access_products(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $response = $this->actingAs($superadmin)->get('/dashboard/purchase-orders');
        $response->assertOk();
    }

    // ===== TEST CREATE =====
    public function test_superadmin_can_view_create_product_form(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $response = $this->actingAs($superadmin)->get('/dashboard/products/create');
        $response->assertOk();
        $response->assertViewIs('products.create');
    }

    public function test_superadmin_can_store_new_product()
    {
        Storage::fake('public');
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();

        $productData = [
            'name'      => 'Produk Test Baru',
            'stock'     => 10,
            'price'     => 5000,
            'desc'      => 'Deskripsi produk test baru',
            'image'     => UploadedFile::fake()->image('product.jpg'),
            'categories'=> [$category->id],
            'suppliers' => [$supplier->id]
        ];

        $response = $this->actingAs($superadmin)->post('/dashboard/products', $productData);

        $this->assertDatabaseHas('products', [
            'name'  => 'Produk Test Baru',
            'stock' => 10,
            'price' => 5000,
        ]);

        $product = Product::where('name', 'Produk Test Baru')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
        $this->assertTrue($product->categories->contains($category));
        $this->assertTrue($product->suppliers->contains($supplier));

        $this->assertDatabaseHas('stock_movements', [
            'product_id'    => $product->id,
            'quantity'      => 10,
            'type'          => 'stok_awal',
        ]);

        $response->assertRedirect('/dashboard/products');
        $response->assertSessionHas('success', 'Produk berhasil ditambahkan.');
    }

    public function test_store_product_fails_with_invalid_data()
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $invalidData = [
            'name'      => '',
            'stock'     => -5,
            'price'     => 'abcd',
            'categories'=> [],
            'suppliers' => [],
        ];

        $response = $this->actingAs($superadmin)->post('/dashboard/products', $invalidData);
        $response->assertSessionHasErrors(['name', 'stock', 'price', 'categories', 'suppliers']);
        $this->assertDatabaseCount('products', 0);
    }

    // ===== TEST UPDATE =====
    public function test_superadmin_can_view_edit_product_form(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $product = Product::factory()->create();
        $response = $this->actingAs($superadmin)->get("/dashboard/products/{$product->id}/edit");
        $response->assertOk();
        $response ->assertViewIs('products.edit');
        $response->assertSee($product->name);
    }

    public function test_superadmin_can_update_product(): void
    {
        Storage::fake('public');
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $product = Product::factory()
                          ->has(Category::factory(), 'categories')
                          ->has(Supplier::factory(), 'suppliers')
                          ->create(['stock' => 5]);

        $oldCategory = $product->categories->first();

        $newCategory = Category::factory()->create();
        $newSupplier = Supplier::factory()->create();

        $updateData = [
            'name' => 'Produk Test Update',
            'stock' => 15,
            'price' => 75000,
            'desc' => 'Deskripsi produk update.',
            'image' => UploadedFile::fake()->image('produk_update.jpg'),
            'categories' => [$newCategory->id],
            'suppliers' => [$newSupplier->id],
        ];

        $response = $this->actingAs($superadmin)->put("/dashboard/products/{$product->id}", $updateData);

        $product->refresh();

        $this->assertEquals('Produk Test Update', $product->name);
        $this->assertEquals(15, $product->stock);
        $this->assertEquals(75000, $product->price);
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);

        $this->assertTrue($product->categories->contains($newCategory));
        $this->assertFalse($product->categories->contains($oldCategory));
        $this->assertTrue($product->suppliers->contains($newSupplier));

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity' => 10,
            'type' => 'penyesuaian_manual',
        ]);

        $response->assertRedirect('/dashboard/products');
        $response->assertSessionHas('success', 'Produk berhasil diupdate.');
    }

    // ===== TEST DELETE =====
    public function test_superadmin_can_delete_product(): void
    {
        Storage::fake('public');
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $product = Product::factory()->create(['image' => UploadedFile::fake()->image('hapus.jpg')->store('products', 'public')]);
        $imagePath = $product->image;

        $response = $this->actingAs($superadmin)->delete("/dashboard/products/{$product->id}");
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        Storage::disk('public')->assertMissing($imagePath);

        $response->assertRedirect('/dashboard/products');
        $response->assertSessionHas('success', 'Produk berhasil dihapus.');
    }
}
