<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'description', 'image', 'category_id', 'price'];
    /*public function getCategory()
    {
        return Category::find($this->category_id);
    }*/
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getPriceForCount()
    {
        if (!is_null($this->pivot)) {
            return $this->pivot->count * $this->price;
        }
        return $this->price;
    }
}
