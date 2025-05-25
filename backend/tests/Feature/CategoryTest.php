<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_view_all_category_successfully(): void
    {
        $categories = Category::factory(5)->create();
        //we pass Accept-Language for the middleware
        $response = $this->getJson('/api/categories/ViewAllCategory', [
            'Accept-Language' => 'ar',
        ]);
        $response->assertStatus(200);
        foreach ($categories as $category) {
            $this->assertDatabaseHas('categories', $category->toArray());
        }
        //Verify the JSON conten
        $response->assertJson($categories->map(fn($category) => [
            'id' => $category->id,
            'name' => $category->Arabic_name
        ])->toArray());
    }

    ////////
    public function test_show_specific_category_successfully(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/show?id={$category->id}", [
            'Accept-Language' => 'ar',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', $category->toArray());
        $response->assertJson([
            'id' => $category->id,
            'name' => $category->Arabic_name
        ]);
    }

    /////////
    public function test_delete_specific_category_successfully(): void
    {
        //creat a user and take the token
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $category = Category::factory()->create();
        //then pass the token for the middleware(Authorization)
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->deleteJson("/api/categories/delete?id={$category->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Category deleted successfully.']);
        $this->assertDatabaseMissing('categories', $category->toArray());
    }

    ///////
    public function test_update_specific_category_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $category = Category::factory()->create();
        $data = [
            'Arabic_name' => fake()->unique()->word,
            'English_name' => fake()->unique()->word,
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->putJson("/api/categories/update?id={$category->id}", $data);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Category update successfully.']);
        $this->assertDatabaseHas('categories', $data);
    }

    ////////
    public function test_insert_category_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $category = [
            'Arabic_name' => fake()->unique()->word,
            'English_name' => fake()->unique()->word,
        ];
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson("/api/categories/insert", $category);
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Category inserted successfully.']);
        $this->assertDatabaseHas('categories', $category);
    }

    ///////
    public function test_validation_with_wrong_data(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $token = JWTAuth::fromUser($user);
        $data = [
            'Arabic_name' => 1111,
            'English_name' => 2222,
            'photo' => 3333,
        ];
        $response1 = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->putJson("/api/categories/update", $data);
        $response2 = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson("/api/categories/insert", $data);
        $response1->assertStatus(422);
        $response1->assertJson(['message' => 'Validation Error.']);
        $response2->assertStatus(422);
        $response2->assertJson(['message' => 'Validation Error.']);
    }
}
