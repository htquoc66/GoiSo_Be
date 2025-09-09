<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sott extends Model
{
    use HasFactory;
        protected $fillable = ['phankhu', 'sott_thuong', 'sott_uutien'];
}
