<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'judul',
        'category_id',
        'penulis',
        'penerbit',
        'halaman',
        'tahun_terbit',
        'sinopsis',
        'cover',
        'ebook',
        'stok',
        'isbn',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

     public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function isAvailable()
    {
        return $this->stok > 0 && $this->is_active;
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('judul', 'LIKE', "%{$keyword}%")
              ->orWhere('penulis', 'LIKE', "%{$keyword}%")
              ->orWhereHas('category', function ($q) use ($keyword) {
                  $q->where('nama', 'LIKE', "%{$keyword}%");
              });
        });
    }

     public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0)->where('is_active', true);
    }

    public function scopePopuler($query)
    {
        return $query->withCount('borrowings')->orderBy('borrowings_count', 'desc');
    }
}
