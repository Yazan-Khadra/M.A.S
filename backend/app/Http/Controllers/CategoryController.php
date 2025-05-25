<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function InsertNewCategory(Request $request)
    {
        $validation = $this->validation($request);
        if ($validation) return $validation;
        $data = new Category();
        $data->Arabic_name = $request->Arabic_name;
            $path = $request->photo->store('images/categories', 'public');
            $data->photo = '/storage/' . $path;

        $data->save();
        return response()->json(
            ['message' => 'Category inserted successfully.'],
            201
        );
    }

    /////////
    public function UpdateCategory(Request $request)
    {
        $validation = $this->validation($request);
        if ($validation) return $validation;
        $category = Category::findorFail($request->id);
            if ($category->photo) {
                $previousImagePath = public_path($category->photo);
                if (File::exists($previousImagePath)) {
                    File::delete($previousImagePath);
                }
            }
            $path = $request->photo->store('images/categories', 'public');
            $category->photo = '/storage/' . $path;
        $category->update($request->except(['photo']));
        $category->save();
        return response()->json(
            ['message' => 'Category update successfully.'],
            200
        );
    }

    /////////
   public function destroy(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);
            
            // Delete the image file if it exists
            if ($category->photo) {
                $imagePath = str_replace('/storage/', '', $category->photo);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            
            $category->delete();
            
            return response()->json([
                'message' => 'Category deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting category',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    ///////////
    public function ViewAllCategory()
    {
        //getting language from user (Header) 
        $language = app()->getLocale();
        $categories = Category::all();
        $response = [];
        foreach ($categories as $category) {
            $response[] = [
                'id' => $category->id,
                'name' => $category->Arabic_name ,
                'photo' => $category->photo,
            ];
        }
        return response()->json($response, 200);
    }

    //////////
    public function show(Request $request)
    {
        $language = app()->getLocale();
        $category = Category::findOrFail($request->id);
        return response()->json([
            'id' => $category->id,
            'name' => $category->Arabic_name,
            'photo' => $category->photo,
        ], 200);
    }

    ///////
    public function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'Arabic_name' => 'string',
            // 'English_name' => 'string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Error.', 'errors' => $validator->errors()], 422);
        }
        //the category must be unique so we test if this category is already exists based on the language
        $arabic_category = Category::where('Arabic_name', $request->Arabic_name)->first();
        

        if ($arabic_category) {
            return response()->json(['message' => 'Arabic name already exists'], 409);
        }

        return null;
    }

    public function get_products_by_categroy($id)
    {
        $language = app()->getLocale();
        $category = Category::findOrFail($id);
        $products = $category->product;
        
        $response = [];
        foreach ($products as $product) {
            $response[] = [
                'id' => $product->id,
                'name' => $product->Arabic_name ,
                'description' => $product->Arabic_description,
                'photo' => $product->photo,
                'category' => $category->Arabic_name
            ];
        }
        
        return response()->json($response, 200);
    }
}
