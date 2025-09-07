<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhongKham;

class PhongKhamController extends Controller
{
    // GET: Danh sách phòng khám theo nhiều mã phòng (ví dụ ?ids=1,2,3)
    public function index(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        return PhongKham::whereIn('ma_phong', $ids)->get();
    }

    // POST: Gọi số - Tăng số_hien_tai lên 1 với mã phòng
   public function goiSo($ma_phong)  
    {
        $phong = PhongKham::where('ma_phong', $ma_phong)->firstOrFail();
        $phong->increment('so_hien_tai');
        return response()->json($phong);
    }

    // GET: Danh sách tất cả mã phòng
    public function danhSachPhong()
    {
        return PhongKham::select('ma_phong')->orderBy('ma_phong')->get();
    }

    // POST: Gọi số ưu tiên - Tăng so_uu_tien lên 1 với mã phòng
    public function goiUuTien($ma_phong)
    {
        $phong = PhongKham::where('ma_phong', $ma_phong)->firstOrFail();
        $phong->increment('so_uu_tien');
        return response()->json($phong);
    }

    public function reset($id)
    {
        $phong = PhongKham::where('ma_phong', $id)->first();

        if (!$phong) {
            return response()->json(['message' => 'Không tìm thấy phòng'], 404);
        }

        $phong->so_hien_tai = 0;
        $phong->so_uu_tien = 0;
        // $phong->so_goi_lai_thuong = 0;
        // $phong->so_goi_lai_uutien = 0;
        $phong->save();

        return response()->json($phong);
    }

}
