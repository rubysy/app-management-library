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


    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
