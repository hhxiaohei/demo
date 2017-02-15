<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{
    protected $guarded = [];

    const MAP =[
        "GET"=>1,
        "POST"=>2,
        "PUT"=>3,
        "DELETE"=>4,
        "PATCH"=>5,
    ];
    const REMAP = [
        1=>"GET",
        2=>"POST",
        3=>"PUT",
        4=>"DELETE",
        5=>"PATCH",
    ];
    //
    protected $casts =[
        "extra"=>"array",
        "data"=>"array",
    ];

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
    public function getMethodNameAttribute()
    {
        return collect(self::MAP)->flip()->get($this->method);
    }
    public function scopeOfMethod($query,$method)
    {
        return $query->whereMethod(self::MAP[$method]);
    }
}
