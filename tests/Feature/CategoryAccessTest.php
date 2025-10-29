<?php


namespace Tests\Feature;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAccessTest extends TestCase
{

    use RefreshDatabase;

    // ===== TEST AKSES =====
    public function test_guests_are_redirected_to_login_when_accessing_categories(): void
    {
        $response = $this->get('/dashboard/categories');
        $response->assertRedirect('/login');
    }

    public function test_users_with_role_user_are_forbidden_from_accessing_categories(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/dashboard/categories');
        $response->assertForbidden();
    }

    public function test_superadmin_users_can_access_categories(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $response = $this->actingAs($superadmin)->get('/dashboard/categories');
        $response->assertOk();
    }

    // ===== TEST CREATE =====

    public function test_superadmin_can_view_create_category_form(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $response = $this->actingAs($superadmin)->get('/dashboard/categories/create');
        $response->assertOk();
        $response->assertViewIs('categories.create');
    }

    public function test_superadmin_can_store_new_category(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $supplier1 = Supplier::factory()->create();
        $supplier2 = Supplier::factory()->create();

        $categoryData = [
            'name' => 'Kategori Test Baru',
            'suppliers' => [$supplier1->id, $supplier2->id],
        ];

        $response = $this->actingAs($superadmin)->post('/dashboard/categories', $categoryData);

        $this->assertDatabaseHas('categories', [
            'name' => 'Kategori Test Baru',
        ]);

        $category = Category::where('name', 'Kategori Test Baru')->first();
        $this->assertCount(2, $category->suppliers);
        $this->assertTrue($category->suppliers->contains($supplier1));
        $this->assertTrue($category->suppliers->contains($supplier2));

        $response->assertRedirect('/dashboard/categories');
        $response->assertSessionHas('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function test_store_category_fails_with_invalid_data(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $invalidData = [
            'name' => '',
            'suppliers' => [999],
        ];

        $response = $this->actingAs($superadmin)->post('/dashboard/categories', $invalidData);

        $response->assertSessionHasErrors(['name', 'suppliers.*']);
        $this->assertDatabaseCount('categories', 0);
    }

    // ===== TEST UPDATE =====

    public function test_superadmin_can_view_edit_category_form(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($superadmin)->get("/dashboard/categories/{$category->id}/edit");

        $response->assertOk();
        $response->assertViewIs('categories.edit');
        $response->assertSee($category->name);
    }

    public function test_superadmin_can_update_category(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $category = Category::factory()->has(Supplier::factory()->count(2))->create();
        $oldSupplier = $category->suppliers->first();
        $newSupplier = Supplier::factory()->create();

        $updateData = [
            'name' => 'Kategori Test Update',
            'suppliers' => [$newSupplier->id],
        ];

        $response = $this->actingAs($superadmin)->put("/dashboard/categories/{$category->id}", $updateData);

        $category->refresh();
        $this->assertEquals('Kategori Test Update', $category->name);
        $this->assertCount(1, $category->suppliers);
        $this->assertTrue($category->suppliers->contains($newSupplier));
        $this->assertFalse($category->suppliers->contains($oldSupplier));

        $response->assertRedirect('/dashboard/categories');
        $response->assertSessionHas('success', 'Kategori berhasil diupdate.');
    }

    // ===== TEST DELETE =====

    public function test_superadmin_can_delete_category(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $category = Category::factory()->create();
        $response = $this->actingAs($superadmin)->delete("/dashboard/categories/{$category->id}");

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $response->assertRedirect('/dashboard/categories');
        $response->assertSessionHas('success', 'Kategori berhasil dihapus');
    }
}
