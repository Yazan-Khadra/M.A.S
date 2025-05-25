<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category_name = Category::find($this->category_id)->Arabic_name;
        return [
            "id" => $this->id,
            "product_name" => $this->Arabic_name,
            "descrption" => $this->Arabic_description,
            // "photo" => $this->photo,
            "category" => $category_name,
        ];
    
    }
}
