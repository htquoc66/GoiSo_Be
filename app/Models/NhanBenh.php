<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanBenh extends Model
{
        protected $table = 'nhan_benh';  
    protected $fillable = [
        'quay',
        'phankhu',
        'sott_thuong',
        'sott_uutien',
    ];
}
