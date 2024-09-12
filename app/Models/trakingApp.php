<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trakingApp extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'versionCode', 'versionName', 'comentarios', 'active', 'in_process', 'path_app'];
}
