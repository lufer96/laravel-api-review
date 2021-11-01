<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected function setUp() : void
    {
        parent::setUp();
        $user = User::factory(1)->create();
        Sanctum::actingAs(
            $user[0]
        );
    }
    public function text_index()
    {

        Product::factory(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertSuccessful();
        // $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(5);
    }

    public function test_create_new_product()
    {
        $data = [
            'name' => 'test name',
            'price' => 1000,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertSuccessful();
        // $response->assertDatabaseHas('products', $data);
    }

    public function test_update_product()
    {

        /** @var Product $product */
        $product = Product::factory(1)->create();

        $data = [
            'name' => 'test update name',
            'price' => 20000
        ];

        $response = $this->patchJson("/api/products/{$product[0]->id}", $data);
        $response->assertSuccessful();
        // $response->assertHeader('content-type', 'application/json');
    }

    public function test_show_product()
    {

        /** @var Product $product */
        $product = Product::factory(1)->create();

        $response = $this->getJson("/api/products/{$product[0]->id}");

        $response->assertSuccessful();
    }

    public function test_delete_product()
    {

        /** @var Product $product */
        $product = Product::factory(1)->create();

        $response = $this->deleteJson("/api/products/{$product[0]->id}");
        $response->assertSuccessful();
        // $this->assertDeleted($product);

    }
}
