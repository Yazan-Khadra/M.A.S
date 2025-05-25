<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $fillable = [
        'photo',
        'Arabic_name',
        'Arabic_description',
        'English_name',
        'English_description',
        'main_product',
        'likes',
        'category_id'
    ];

    protected $casts = [
        'main_product' => 'boolean'
    ];
    public function Comments():HasMany {
        return $this->hasMany(Comment::class);
        
    }
}
