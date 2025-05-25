<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
   protected $fillable =  [
    "product_id",
    "comment"
   ];
   
}
