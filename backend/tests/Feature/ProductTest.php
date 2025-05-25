<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_view_all_products_successfully(): void
    {
        Cache::flush();
        $products = Product::factory(5)->create();
        //we pass Accept-Language for the middleware
        $response = $this->getJson('/api/products/ViewAllProducts', [
            'Accept-Language' => 'ar',
        ]);
        $response->assertStatus(200);
        foreach ($products as $product) {
            $this->assertDatabaseHas('products', $product->toArray());
        }
        $this->assertTrue(Cache::has('products'));
        $productsInCache = Cache::get('products');

        $this->assertEquals($products->toArray(), $productsInCache->toArray());
        //Verify the JSON conten
        $response->assertJson($products->map(fn($product) => [
            'id' => $product->id,
            'name' => $product->Arabic_name,
            'description' => $product->Arabic_description,
            'photo' => $product->photo,
            'category_id' => $product->category_id,
        ])->toArray());
    }

    ///////
    public function test_show_specific_product_successfully(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/show?id={$product->id}", [
            'Accept-Language' => 'ar',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', $product->toArray());
        $this->assertTrue(Cache::has("product_{$product->id}"));
        $response->assertJson([
            'id' => $product->id,
            'name' => $product->Arabic_name,
            'description' => $product->Arabic_description,
            'photo' => $product->photo,
            'category_id' => $product->category_id,
        ]);
    }

    ///////
    public function test_delete_specific_product_successfully(): void
    {
        //creat a user and take the token
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $product = Product::factory()->create();
        //put fake prodct in cache befor delete it 
        Cache::put("product_{$product->id}", $product, now()->addMinutes(60));
        Cache::put('products', Product::all(), now()->addMinutes(60));
        //pass the token for the middleware(Authorization)
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->deleteJson("/api/products/delete?id={$product->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'product deleted successfully.']);
        $this->assertDatabaseMissing('products', $product->toArray());
        //test that this product deleted from cache too
        $this->assertFalse(Cache::has("product_{$product->id}"));
        $this->assertFalse(Cache::has('products'));
    }

    ////////
    public function test_update_specific_product_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $product = Product::factory()->create();
        $data = [
            'Arabic_name' => 'سيارة',
            'Arabic_description' => 'سيارة حمراء',
            'English_name' => 'car',
            'English_description' => 'red car',
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->putJson("/api/products/update?id={$product->id}", $data);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'product update successfully.']);
        $this->assertDatabaseHas('products', $data);
        $this->assertTrue(Cache::has("product_{$product->id}"));
        $this->assertFalse(Cache::has('products'));
    }

    ////////
    public function test_insert_product_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $category = Category::factory()->create();
        $product = [
            'Arabic_name' => 'سيارة',
            'Arabic_description' => 'سيارة حمراء',
            'English_name' => 'car',
            'English_description' => 'red car',
            'category_id' => $category->id,
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson("/api/products/insert", $product);
        $response->assertStatus(201);
        $response->assertJson(['message' => 'product insert successfully.']);
        $this->assertDatabaseHas('products', $product);
        //we have to know the id of this new product
        $insertedProduct = Product::where('Arabic_name', 'سيارة')
            ->where('Arabic_description', 'سيارة حمراء')
            ->where('English_name', 'car')
            ->where('English_description', 'red car')
            ->where('category_id', $category->id)
            ->first();
        $this->assertTrue(Cache::has("product_{$insertedProduct->id}"));
        $this->assertFalse(Cache::has('products'));
    }

    ////////
    public function test_validation_with_wrong_data(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $data = [
            'Arabic_name' => 111,
            'Arabic_description' => 111,
            'English_name' => 22222,
            'English_description' => 2222,
            'category_id' => 'nour',
            'photo' => 3333,
        ];
        $response1 = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->putJson("/api/products/update", $data);
        $response2 = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson("/api/products/insert", $data);
        $response1->assertStatus(422);
        $response1->assertJson(['message' => 'Validation Error.']);
        $response2->assertStatus(422);
        $response2->assertJson(['message' => 'Validation Error.']);
    }
}
