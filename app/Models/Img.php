<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Img extends Model
{
    public $table = 'imgs';
    protected $fillable = [
        'title', 'img'
    ];
}
