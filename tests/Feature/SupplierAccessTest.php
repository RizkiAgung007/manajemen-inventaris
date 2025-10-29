<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use tests\TestCase;

class SupplierAccessTest extends TestCase
{
    use RefreshDatabase;

    // ===== TEST AKSES =====
    public function test_guests_are_redirected_to_login_when_accessing_suppliers(): void
    {
        $response = $this->get('/dashboard/suppliers');
        $response->assertRedirect('/login');
    }

    public function test_users_with_role_user_are_forbidden_from_accessing_suppliers(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/dashboard/suppliers');
        $response->assertForbidden();
    }

    public function test_superadmin_users_can_access_suppliers(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $response = $this->actingAs($superadmin)->get('/dashboard/suppliers');
        $response->assertOk();
    }

    // ===== TEST CREATE =====
    public function test_superadmin_can_view_create_supplier_form(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $response = $this->actingAs($superadmin)->get('/dashboard/suppliers/create');
        $response->assertOk();
        $response->assertViewIs('suppliers.create');
    }

    public function test_superadmin_can_store_new_supplier(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $supplierData = Supplier::factory()->make([
            'name' => 'Supplier Test PT',
            'contact_person' => 'Budi Test',
        ])->toArray();

        $response = $this->actingAs($superadmin)->post('/dashboard/suppliers', $supplierData);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Supplier Test PT',
            'contact_person' => 'Budi Test',
        ]);

        $response->assertRedirect('/dashboard/suppliers');
        $response->assertSessionHas('success');
    }

    public function test_store_supplier_fails_with_invalid_data(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $invalidData = [
            'name' => '',
            'contact_person' => 'Test',
            'phone' => '12345',
            'address' => 'Alamat test',
        ];

        $response = $this->actingAs($superadmin)->post('/dashboard/suppliers', $invalidData);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('suppliers', 0);
    }

    // ===== TEST UPDATE =====
    public function test_superadmin_can_view_edit_supplier_form(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $supplier = Supplier::factory()->create();
        $response = $this->actingAs($superadmin)->get("/dashboard/suppliers/{$supplier->id}/edit");
        $response->assertOk();
        $response->assertViewIs('suppliers.edit');
        $response->assertSee($supplier->name);
    }

    public function test_superadmin_can_update_supplier(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $supplier = Supplier::factory()->create();

        $updatedData = [
            'name'          => 'Supplier Update Jaya',
            'contact_person'=> 'Siti Update',
            'phone'         => '081234567890',
            'address'       => 'Jl. Update No. 123',
        ];

        $response = $this->actingAs($superadmin)->put("/dashboard/suppliers/{$supplier->id}", $updatedData);
        $this->assertDatabaseHas('suppliers', [
            'id'            => $supplier->id,
            'name'          => 'Supplier Update Jaya',
            'contact_person'=> 'Siti Update',
        ]);

        $response->assertRedirect('/dashboard/suppliers');
        $response->assertSessionHas('success');
    }

    // ===== TEST DELETE =====
    public function test_superadmin_can_delete_supplier(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($superadmin)->delete("/dashboard/suppliers/{$supplier->id}");

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
        $response->assertRedirect('/dashboard/suppliers');
        $response->assertSessionHas('success');
    }
}
