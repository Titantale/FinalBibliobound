<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory; 

    protected $fillable = ['image','title','isbn','author','status','synopsis','genre1','genre2','genre3','genre4'];
}
