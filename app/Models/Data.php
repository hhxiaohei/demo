<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    public $table = 'datas';

    protected $fillable = [
        'name', 'email', 'age', 'city', 'enabled',
    ];

    public function getAgeAttribute($value)
    {
        return $value + 1;
    }

    public function getRateAttribute($value)
    {
        $html = '';
        for ($i = 0; $i < $value; $i++) {
            $html = $html . "<i class='mdi-action-star-rate' style='color:#ff8913;display: inline-block;font-size: 25px'></i>";
        }
        return $html;
    }

    public function getNameAttribute($value)
    {
        return substr($value, 0 ,7);
    }

    public function getFlagAttribute()
    {
        if ($this->id % 3 == 0) {
            return "<i class='mdi-content-flag' style='color:red;display: block;font-size: 20px'></i>";
        } else if ($this->id % 2 == 0) {
            return "<i class='mdi-image-wb-sunny' style='color:orange;display: block;font-size: 20px'></i>";
        } else {
            return "<i class='mdi-social-public' style='color:cornflowerblue;display: block;font-size: 20px'></i>";
        }

    }

    public function getTagAttribute()
    {
        $array = ['iOS', 'PHP', 'Java', 'Go', 'Python'];
        $key = array_rand($array);
        return "<span class='label bg-info'>" . $array[$key] . "</span>";
    }

    public static $type = [
        'A' => 'A',
        'B' => 'B',
    ];
    public static $enabled = [
        '0' => '禁用',
        '1' => '开启',
    ];
}
