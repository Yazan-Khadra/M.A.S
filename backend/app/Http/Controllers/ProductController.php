<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function InsertNewProduct(Request $request)
    {
        
        $validation = $this->validation($request);
        if ($validation) return $validation;
        
        $data = new Product();
        $data->Arabic_name = $request->Arabic_name;
        $data->Arabic_description = $request->Arabic_description;
        $data->category_id = $request->category_id;
        $data->main_product = $request->main_product;
        
        if ($request->hasFile('photo')) {
            $path = $request->photo->store('images/products', 'public');
            $data->photo = '/storage/' . $path;
        }

        $data->save();
        
        Cache::put("product_{$data->id}", $data, now()->addMinutes(60));
        Cache::forget('products');
        
        return response()->json(
            [
                'message' => 'product insert successfully.',
            ],
            201
        );
    }

    ///////////
    public function UpdateProduct(Request $request)
    {
        $validation = $this->validation($request);
        if ($validation) return $validation;
        $product = Product::findorFail($request->id);
        if ($request->hasFile('photo')) {
            if ($product->photo) {
                $previousImagePath = public_path($product->photo);
                if (File::exists($previousImagePath)) {
                    File::delete($previousImagePath);
                }
            }
            $path = $request->photo->store('images/products', 'public');
            $product->photo = '/storage/' . $path;
        }
        $product->update($request->except(['photo']));
        $product->save();
        Cache::put("product_{$product->id}", $product, now()->addMinutes(60));
        //this line to refresh cache when we update old product
        Cache::forget('products');
        return response()->json(
            [
                'message' => 'product update successfully.'
            ],
            200
        );
    }

    /////////
    public function destroy(Request $request)
    {
        try {
            $product = Product::findOrFail($request->id);
            
            // Delete the image file if it exists
            if ($product->photo) {
                $imagePath = str_replace('/storage/', '', $product->photo);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        
            $product->delete();
            
            // Remove from cache
            Cache::forget("product_{$request->id}");
            Cache::forget('products');
            
            return response()->json([
                'message' => 'product deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //////
    public function ViewAllProducts()
    {
        //get language from user
        $language = app()->getLocale();
        //To improve website performance and speed up the loading of frequently accessed products, we retrieve product data from the cache
        $products = Cache::get('products');
        if (!$products) {
            $products = Product::all();
            //if these products not in cache we put them there
            Cache::put("products", $products, now()->addMinutes(60));
        }

        $response = [];
        foreach ($products as $product) {
            $category = Category::find($product->category_id);
            $response[] = [
                'id' => $product->id,
                'name' =>  $product->Arabic_name ,
                'description' =>  $product->Arabic_description ,
                'photo' => $product->photo,
                'category' =>  $category->Arabic_name ,
                "likes" => $product->likes,
            ];
        }
        return response()->json($response, 200);
    }

    ////////
    public function show(Request $request)
    {
        $language = app()->getLocale();
        $product = Cache::get("product_{$request->id}");
        if (!$product) {
            $product = Product::findOrFail($request->id);
            Cache::put("product_{$request->id}", $product, now()->addMinutes(60));
        }
        $category = Category::find($product->category_id);
        return response()->json([
            'id' => $product->id,
            'name' =>  $product->Arabic_name ,
            'description' =>  $product->Arabic_description ,
            'photo' => $product->photo,
            'category' =>  $category->Arabic_name
        ], 200);
    }
    public function Add_like(Request $request){
        $product = Product::find($request->id);
        $likes = $product->likes;
        $likes+=1;
        $product->update([
            'likes'=>$likes,
        ]);
        Cache::put("product_{$product->id}", $product, now()->addMinutes(60));
        //this line to refresh cache when we update old product
        Cache::forget('products');
      
        return response()->json("like added successfully",200);
    }
    ///////
    public function Remove_like(Request $request) {
          $product = Product::find($request->id);
        $likes = $product->likes;
        $likes-=1;
        $product->update([
            'likes'=>$likes,
        ]);
        Cache::put("product_{$product->id}", $product, now()->addMinutes(60));
        //this line to refresh cache when we update old product
        Cache::forget('products');
      
        return response()->json("like removed successfully",200);
    }
    public function validation($request)
    {
        try{
        $validator = Validator::make($request->all(), [
            'Arabic_name' => 'required|string',
            'Arabic_description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Error.', 'errors' => $validator->errors()], 422);
        }
      
        if($request->input("main_product") == true) {
            if(Product::where("main_product", 1)->count() >= 4) {
                return response()->json([
                    "error" => "there is four main products",
                    "message" => "Cannot add more main products. Maximum limit of 4 has been reached."
                ], 422);
            }
        }
        
        return null; // Return null if validation passes
    }
        catch (\Exception $e) {
        return response()->json([
            'message' => 'Internal Server Error',
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
    }

    public function get_mainPage_products() {
        $products = Cache::get('products');
        if (!$products) {
            $products = Product::all();
            Cache::put("products", $products, now()->addMinutes(60));
        }
        
        $response = [];
        foreach($products as $product) {
            if($product->main_product == 1) {
                
                $category_name = Category::findOrFail($product->category_id)->Arabic_name;
                $response[] = [
                    "id" => $product->id,
                    "name" => $product->Arabic_name,
                    "description" => $product->Arabic_description,
                    "photo" => $product->photo,
                    "category" => $category_name,
                ];
            }
        }
        
        return response()->json($response, 200);
    }
}
