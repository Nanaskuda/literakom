<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{

    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function books(){
        return $this->hasMany(Book::class);
    }

      protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->nama);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->nama);
        });
    }

    public function bookCount() : int
    {
        return $this->books()->where('is_active', true)->count();
    }
}
