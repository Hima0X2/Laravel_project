<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Specify the table name
    protected $table = 'post';

    // Add any other necessary configurations
    protected $fillable = [
        'title', 'description', 'image',
    ];
}
