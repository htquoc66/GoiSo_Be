<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongKham extends Model
{
    use HasFactory;
    // protected $fillable = ['ma_phong', 'so_hien_tai'];
    protected $fillable = ['ma_phong', 'so_hien_tai', 'so_uu_tien'];


}
