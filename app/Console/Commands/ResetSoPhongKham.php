<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetSoPhongKham extends Command
{
    protected $signature = 'phongkham:reset-so';
    protected $description = 'Reset tất cả số trong bảng phòng khám về 0';

    public function handle()
    {
        DB::table('phong_kham')->update([
            'so_hien_tai' => 0,
            'so_uu_tien' => 0,
            'so_goi_lai_thuong' => 0,
            'so_goi_lai_uutien' => 0,
            'updated_at' => now(),
        ]);

        $this->info('Đã reset số phòng khám về 0 thành công.');
    }
}
