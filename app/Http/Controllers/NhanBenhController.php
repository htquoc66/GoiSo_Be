<?php

namespace App\Http\Controllers;

use App\Models\NhanBenh;
use App\Models\NhanBenhLog;
use Illuminate\Http\Request;

class NhanBenhController extends Controller
{
    // Lấy danh sách các quầy
    public function index()
    {
        return response()->json(NhanBenh::all());
    }

    // Lấy log mới nhất
    public function logs()
    {
        return response()->json(
            NhanBenhLog::orderBy('created_at', 'desc')->limit(100)->get()
        );
    }

    // Gọi số thường
    public function goiThuong(Request $request, $quay)
    {
        $record = NhanBenh::firstOrCreate(['quay' => $quay]);
        $phankhu = $request->input('phankhu', $record->phankhu);

        // mốc khởi tạo
        $base = [
            91 => 10000,
            93 => 30000,
        ];

        // lấy số lớn nhất trong phân khu
        $maxSo = NhanBenh::where('phankhu', $phankhu)->max('sott_thuong') ?? 0;
        $soMoi = max($maxSo, $base[$phankhu] ?? 0) + 1;

        $record->phankhu = $phankhu;
        $record->sott_thuong = $soMoi;
        $record->save();

        // ghi log
        NhanBenhLog::create([
            'quay' => $quay,
            'phankhu' => $phankhu,
            'so' => $soMoi,
            'loai' => 'thuong',
        ]);

        return response()->json($record);
    }

    // Gọi số ưu tiên
    public function goiUuTien(Request $request, $quay)
    {
        $record = NhanBenh::firstOrCreate(['quay' => $quay]);
        $phankhu = $request->input('phankhu', $record->phankhu);

        // mốc khởi tạo
        $base = [
            92 => 20000,
        ];

        $maxSo = NhanBenh::where('phankhu', $phankhu)->max('sott_uutien') ?? 0;
        $soMoi = max($maxSo, $base[$phankhu] ?? 0) + 1;

        $record->phankhu = $phankhu;
        $record->sott_uutien = $soMoi;
        $record->save();

        NhanBenhLog::create([
            'quay' => $quay,
            'phankhu' => $phankhu,
            'so' => $soMoi,
            'loai' => 'uutien',
        ]);

        return response()->json($record);
    }

    // Reset số của tất cả quầy trong phân khu
    public function reset($quay)
    {
        $record = NhanBenh::where('quay', $quay)->first();
        if (!$record) {
            return response()->json(['message' => 'Không tìm thấy quầy'], 404);
        }

        $phankhu = $record->phankhu;

        // Định nghĩa mốc khởi tạo
        $baseThuong = [
            91 => 10000,
            93 => 30000,
        ];
        $baseUuTien = [
            92 => 20000,
        ];

        $sott_thuong = $baseThuong[$phankhu] ?? 0;
        $sott_uutien = $baseUuTien[$phankhu] ?? 0;

        // Reset tất cả quầy cùng phân khu về mốc
        NhanBenh::where('phankhu', $phankhu)->update([
            'sott_thuong' => $sott_thuong,
            'sott_uutien' => $sott_uutien,
            'updated_at'  => now()
        ]);

        return response()->json([
            'message' => "Đã reset tất cả quầy thuộc phân khu {$phankhu} về mốc khởi tạo",
            'sott_thuong' => $sott_thuong,
            'sott_uutien' => $sott_uutien,
        ]);
    }


    // Cập nhật phân khu cho quầy
    public function updatePhankhu(Request $request, $quay)
    {
        $record = NhanBenh::firstOrCreate(['quay' => $quay]);
        $record->phankhu = $request->input('phankhu');
        $record->save();

        return response()->json($record);
    }

    // Xoá log
    public function deleteLog($id)
    {
        NhanBenhLog::where('id', $id)->delete();
        return response()->json(['message' => "Đã xoá log $id"]);
    }
}
