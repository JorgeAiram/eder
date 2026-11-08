<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test can create a product.
     */
    public function test_can_create_product(): void
    {
        $payload = [
            'name' => 'Laptop Gamer Pro',
            'sku' => 'LAP-GAM-001',
            'price' => 1299.99,
            'stock' => 15,
            'description' => 'Laptop de alto rendimiento',
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Producto creado exitosamente.',
                'product' => [
                    'sku' => 'LAP-GAM-001',
                    'name' => 'Laptop Gamer Pro',
                ],
            ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'LAP-GAM-001',
        ]);
    }

    /**
     * Test can list products.
     */
    public function test_can_list_products(): void
    {
        Product::create([
            'name' => 'Teclado Mecánico',
            'sku' => 'TEC-MEC-001',
            'price' => 89.90,
            'stock' => 50,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'count',
                'products',
            ]);
    }

    /**
     * Test validates duplicate SKU.
     */
    public function test_validates_duplicate_sku(): void
    {
        Product::create([
            'name' => 'Mouse Óptico',
            'sku' => 'MOU-OPT-001',
            'price' => 29.50,
            'stock' => 20,
        ]);

        $duplicatePayload = [
            'name' => 'Mouse Inalámbrico',
            'sku' => 'MOU-OPT-001',
            'price' => 39.99,
        ];

        $response = $this->postJson('/api/products', $duplicatePayload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sku']);
    }
}
