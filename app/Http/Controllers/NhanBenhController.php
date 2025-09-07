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

    // Lấy log mới nhất (ví dụ 100 dòng gần nhất)
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

        // lấy số lớn nhất trong phân khu
        $maxSo = NhanBenh::where('phankhu', $phankhu)->max('sott_thuong') ?? 0;
        $soMoi = $maxSo + 1;

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

        $maxSo = NhanBenh::where('phankhu', $phankhu)->max('sott_uutien') ?? 0;
        $soMoi = $maxSo + 1;

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

        NhanBenh::where('phankhu', $phankhu)->update([
            'sott_thuong' => 0,
            'sott_uutien' => 0,
            'updated_at' => now()
        ]);

        // có thể ghi log reset nếu cần
        return response()->json([
            'message' => "Đã reset tất cả quầy thuộc phân khu {$phankhu}"
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

    public function deleteLog($id)
    {
        NhanBenhLog::where('id', $id)->delete();
        return response()->json(['message' => "Đã xoá log $id"]);
    }

}
