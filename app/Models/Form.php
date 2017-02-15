<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public $table = 'form';

    protected $fillable = [
        'title', 'type', 'time','contents','column','level','notetime','note',
    ];

    public function getTypeAttribute($value)
    {
        return $value == '0'? '散文':'诗歌';
    }

    public function getTagAttribute()
    {
        $data = '';
        if($this->column){
            $array = explode(",",$this->column);
            foreach ($array as $v){
                $data .= "<span class='label bg-info'>".$v."</span>" ."&nbsp;&nbsp;";
            }
            return $data;
        }
    }

    public function  getRateAttribute()
    {
        $html = '';
        for ($i = 0;$i < $this->level;$i++){
            $html = $html . "<i class='mdi-action-star-rate' style='color:#ff8913;display: inline-block;font-size: 25px'></i>";
        }
        return $html;
    }

}
