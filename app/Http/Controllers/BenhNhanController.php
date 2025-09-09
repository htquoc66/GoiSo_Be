<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BenhNhan;
use App\Models\Sott;

class BenhNhanController extends Controller
{
    // API đăng ký bệnh nhân & cấp số
    public function store(Request $request)
    {
        $data = $request->validate([
            'mathe'    => 'required|string',
            'hoten'    => 'required|string',
            'ngaysinh' => 'nullable|string',
            'phankhu'  => 'required|integer',
        ]);

        $today = now()->toDateString();

        // ✅ 1. Kiểm tra trong ngày đã có chưa
        $benhNhan = BenhNhan::where('mathe', $data['mathe'])
            ->where('phankhu', $data['phankhu'])
            ->whereDate('created_at', $today)
            ->first();

        if ($benhNhan) {
            return response()->json($benhNhan);
        }

        // ✅ 2. Lấy sott của phân khu
        $sott = Sott::firstOrCreate(['phankhu' => $data['phankhu']]);

        // ✅ 3. Reset nếu qua ngày mới
        if (!$sott->updated_at || $sott->updated_at->toDateString() !== $today) {
            $sott->update([
                'sott_thuong' => 0,
                'sott_uutien' => 0,
            ]);
            $sott->refresh();
        }

        // ✅ 4. Tăng số thứ tự theo quy định
        if ($data['phankhu'] == 92) {
            $sott->increment('sott_uutien');
            $soThuTu = $sott->sott_uutien;
        } else {
            $sott->increment('sott_thuong');
            $soThuTu = $sott->sott_thuong;
        }

        // ✅ 5. Lưu thông tin bệnh nhân mới
        $benhNhan = BenhNhan::create([
            'mathe'    => $data['mathe'],
            'hoten'    => $data['hoten'],
            'ngaysinh' => $data['ngaysinh'],
            'phankhu'  => $data['phankhu'],
            'sott'     => $soThuTu,
        ]);

        return response()->json($benhNhan, 201);
    }



    // API xem tất cả bệnh nhân trong ngày
    public function index()
    {
        $today = now()->toDateString();
        return BenhNhan::whereDate('created_at', $today)->get();
    }

// API lấy danh sách số hiện tại
public function sott()
{
    $all = Sott::all();
    $result = [];

    foreach ($all as $s) {
        if ($s->phankhu == 92) {
            $result[$s->phankhu] = $s->sott_uutien;   // 👈 trả thẳng số
        } else {
            $result[$s->phankhu] = $s->sott_thuong;   // 👈 trả thẳng số
        }
    }

    return response()->json($result);
}


}
