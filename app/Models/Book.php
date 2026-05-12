<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Book extends Model
{
    use HasFactory;
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