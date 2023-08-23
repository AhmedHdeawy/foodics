<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private Model $product;
    private Model $beef;
    private Model $cheese;
    private Model $onion;

    /**
     * Test creating a new order.
     */
    public function test_place_new_order(): void
    {
        // Create ingredients and products
        $this->seedAndReturnProductWithIngredient();

        // Make an order request payload
        $orderPayload = [
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 2]
            ]
        ];

        // Make a POST request to the placeOrder action
        $response = $this->postJson('/api/place-order', $orderPayload);

        // Assert the response and database changes
        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['id' => 1]);
        $this->assertDatabaseHas('order_items', ['id' => 1, 'product_id' => $this->product->id, 'order_id' => 1, 'quantity' => 2]);
        $this->assertDatabaseHas('order_items_ingredients', ['ingredient_id' => $this->beef->id, 'order_item_id' => 1, 'order_id' => 1, 'quantity' => 300]);
        $this->assertDatabaseHas('order_items_ingredients', ['ingredient_id' => $this->cheese->id, 'order_item_id' => 1, 'order_id' => 1, 'quantity' => 60]);
        $this->assertDatabaseHas('order_items_ingredients', ['ingredient_id' => $this->onion->id, 'order_item_id' => 1, 'order_id' => 1, 'quantity' => 40]);
        // $this->assertDatabaseHas('stock', ['ingredient_id' => $this->beef->id, 'current_stock' => 18500]); // 20000 - (150 * 2)
        // $this->assertDatabaseHas('stock', ['ingredient_id' => $this->cheese->id, 'current_stock' => 4940]); // 5000 - (30 * 2)
        // $this->assertDatabaseHas('stock', ['ingredient_id' => $this->onion->id, 'current_stock' => 960]); // 1000 - (20 * 2)
    }

    private function seedAndReturnProductWithIngredient(): void
    {
        // Create ingredients and products
        $this->beef = Ingredient::factory()->create(['name' => 'Beef']);
        $this->beef->stock()->create(['initial_stock' => 20000, 'current_stock' => 20000]);
        
        $this->cheese = Ingredient::factory()->create(['name' => 'Cheese']);
        $this->cheese->stock()->create(['initial_stock' => 5000, 'current_stock' => 5000]);
        
        $this->onion = Ingredient::factory()->create(['name' => 'Onion']);
        $this->onion->stock()->create(['initial_stock' => 1000, 'current_stock' => 1000]);
        
        $product =  Product::factory()->create(['name' => 'Burger']);

        $product->ingredients()->attach([
            $this->beef->id => ['quantity' => 150],
            $this->cheese->id => ['quantity' => 30],
            $this->onion->id => ['quantity' => 20],
        ]);

        $this->product = $product;
    }
}
