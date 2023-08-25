<?php

namespace Tests\Feature;

use App\Jobs\UpdateTheStock;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Models\Stock;
use App\Models\Product;
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


    public function test_place_new_order_request_without_payload_should_fail() : void
    {
        $this->postJson('/api/orders/place-order')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['products']);
    }

    public function test_place_new_order_request_without_product_id_should_fail() : void
    {
        $data = [
            'products' => [
                ['quantity' => 2]
            ]
        ];

        $this->postJson('/api/orders/place-order', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['products.0.product_id']);
    }
    
    public function test_place_new_order_request_without_quantity_should_fail() : void
    {
        $data = [
            'products' => [
                ['product_id' => 2]
            ]
        ];

        $this->postJson('/api/orders/place-order', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['products.0.quantity']);
    }

    public function test_place_new_order_with_low_quantity_should_fail_with_410(): void
    {
        // Create ingredients and products
        $this->seedAndReturnProductWithIngredient();

        // Make an order request payload
        $orderPayload = [
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 500]
            ]
        ];

        // Make a POST request to the placeOrder action
        $response = $this->postJson('/api/orders/place-order', $orderPayload);

        // Assert the response and database changes
        $response->assertStatus(Response::HTTP_GONE);
    }

    public function test_place_new_order_successfully(): void
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
        $response = $this->postJson('/api/orders/place-order', $orderPayload);
        $createdOrder = $response->getOriginalContent();

        // Assert the response and database changes
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('orders', ['id' => $createdOrder->id]);
        $this->assertDatabaseHas('order_items', ['id' => 1, 'product_id' => $this->product->id, 'order_id' => $createdOrder->id, 'quantity' => 2]);

        // TODO: later we may use these assertions
        // $this->assertDatabaseHas('order_items_ingredients', ['ingredient_id' => $this->beef->id, 'order_item_id' => 1, 'order_id' => 1, 'quantity' => 300]);
        // $this->assertDatabaseHas('order_items_ingredients', ['ingredient_id' => $this->cheese->id, 'order_item_id' => 1, 'order_id' => 1, 'quantity' => 60]);
        // $this->assertDatabaseHas('order_items_ingredients', ['ingredient_id' => $this->onion->id, 'order_item_id' => 1, 'order_id' => 1, 'quantity' => 40]);
    }
    
    public function test_place_new_order_successfully_and_stock_updated(): void
    {
        Queue::fake();

        // Create ingredients and products
        $this->seedAndReturnProductWithIngredient();

        // Make an order request payload
        $orderPayload = [
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 2]
            ]
        ];

        // Make a POST request to the placeOrder action
        $response = $this->postJson('/api/orders/place-order', $orderPayload);
        $createdOrder = $response->getOriginalContent();

        // Assert the response and database changes
        $response->assertStatus(Response::HTTP_CREATED);
        
        // Assert stocks updates
        Queue::assertPushed(UpdateTheStock::class);
        Queue::assertPushed(function (UpdateTheStock $job) use ($createdOrder) {
            return $job->orderId === $createdOrder->id;
        });
        // $this->assertDatabaseHas('stocks', ['ingredient_id' => $this->beef->id, 'current_stock' => 18500]); // 20000 - (150 * 2)
        // $this->assertDatabaseHas('stocks', ['ingredient_id' => $this->cheese->id, 'current_stock' => 4940]); // 5000 - (30 * 2)
        // $this->assertDatabaseHas('stocks', ['ingredient_id' => $this->onion->id, 'current_stock' => 960]); // 1000 - (20 * 2)
    }

    private function seedAndReturnProductWithIngredient(): void
    {
        // Create ingredients and products
        $this->beef = Ingredient::factory()->create(['name' => 'Beef']);
        $this->beef->stock()->save($this->saveNewStock(20000));
        
        $this->cheese = Ingredient::factory()->create(['name' => 'Cheese']);
        $this->cheese->stock()->save($this->saveNewStock(5000));
        
        $this->onion = Ingredient::factory()->create(['name' => 'Onion']);
        $this->onion->stock()->save($this->saveNewStock(1000));
        
        $product =  Product::factory()->create(['name' => 'Burger']);

        $product->ingredients()->attach([
            $this->beef->id => ['quantity' => 150],
            $this->cheese->id => ['quantity' => 30],
            $this->onion->id => ['quantity' => 20],
        ]);

        $this->product = $product;
    }

    public function saveNewStock(int $quantity) : Stock
    {
        $stock = new Stock();
        $stock->initial_stock = $quantity;
        $stock->current_stock = $quantity;

        return $stock;
    }
}
