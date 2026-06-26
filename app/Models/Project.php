<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'description', 'images', 'thumbnail', 
        'github_url', 'is_featured', 'technologies', 'id'
    ];
}
