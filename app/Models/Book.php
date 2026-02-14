<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'genre',
        'stock',
        'cover_path',
    ];

    /**
     * Get all categories for this book (many-to-many)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_relasi')->withTimestamps();
    }

    /**
     * Keep backward compatibility - single category accessor
     */
    public function category()
    {
        return $this->categories()->first();
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the average rating for this book
     */
    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
}
