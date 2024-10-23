<?php

namespace Tests\Feature;

use App\Models\Product; 
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected $productId; 

    protected function setUp(): void
    {
        parent::setUp();
        
        
        $product = Product::firstOrCreate([
            'sku' => 'notun kichu',
        ], [
            'name' => '123 Product',
            'price' => 99.99,
            'description' => 'This is a test product.',
            'image' => null,
        ]);

        $this->productId = $product->id; 
    }

    public function test_product_lifecycle()
    {
        $this->assertNotNull($this->productId, 'Product ID should not be null');

        $response = $this->put(route('update', $this->productId), [
            'name' => 'abcf jau test',
            'sku' => 'notun kichu',
            'price' => 150.00,
            'description' => 'Updated description',
        ]);

        $response->assertRedirect(route('index'));

        $this->assertDatabaseHas('products', [
            'id' => $this->productId, 
           'name' => 'abcf jau test',
            'sku' => 'notun kichu',
            'price' => 150.00,
            'description' => 'Updated description',
        ]);

        $deleteResponse = $this->delete(route('destroy', $this->productId));

        $deleteResponse->assertRedirect(route('index'));
        
        $this->assertDatabaseMissing('products', [
            'id' => $this->productId,
        ]);
    }
}
