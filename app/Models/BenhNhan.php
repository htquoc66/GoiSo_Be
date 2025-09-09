<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenhNhan extends Model
{
    use HasFactory;
    protected $fillable = ['mathe', 'hoten', 'ngaysinh', 'sott', 'phankhu'];

}
