<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'published_year',
        'qty',
        'cover'
    ];
    public function loans()
    {
        return $this->hasMany(Loan::class, 'book_id');
    }
}