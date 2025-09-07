<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanBenhLog extends Model
{
    protected $table = 'nhan_benh_logs';

    protected $fillable = [
        'quay', 'phankhu', 'so', 'loai'
    ];
}
