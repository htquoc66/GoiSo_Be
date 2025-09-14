<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BenhNhan;
use App\Models\Sott;
use Illuminate\Support\Facades\DB;  

class BenhNhanController extends Controller
{
    // API đăng ký bệnh nhân & cấp số
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'mathe'    => 'required|string',
    //         'hoten'    => 'required|string',
    //         'ngaysinh' => 'nullable|string',
    //         'phankhu'  => 'required|integer',
    //     ]);

    //     $today = now()->toDateString();

    //     // ✅ 1. Kiểm tra trong ngày đã có chưa
    //     $benhNhan = BenhNhan::where('mathe', $data['mathe'])
    //         ->where('phankhu', $data['phankhu'])
    //         ->whereDate('created_at', $today)
    //         ->first();

    //     if ($benhNhan) {
    //         return response()->json($benhNhan);
    //     }

    //     // ✅ 2. Lấy sott của phân khu
    //     $sott = Sott::firstOrCreate(['phankhu' => $data['phankhu']]);

    //     // ✅ 3. Reset nếu qua ngày mới
    //     if (!$sott->updated_at || $sott->updated_at->toDateString() !== $today) {
    //         $sott->update([
    //             'sott_thuong' => 0,
    //             'sott_uutien' => 0,
    //         ]);
    //         $sott->refresh();
    //     }

    //     // ✅ 4. Tăng số thứ tự theo quy định
    //     if ($data['phankhu'] == 92) {
    //         $sott->increment('sott_uutien');
    //         $soThuTu = $sott->sott_uutien;
    //     } else {
    //         $sott->increment('sott_thuong');
    //         $soThuTu = $sott->sott_thuong;
    //     }

    //     // ✅ 5. Lưu thông tin bệnh nhân mới
    //     $benhNhan = BenhNhan::create([
    //         'mathe'    => $data['mathe'],
    //         'hoten'    => $data['hoten'],
    //         'ngaysinh' => $data['ngaysinh'],
    //         'phankhu'  => $data['phankhu'],
    //         'sott'     => $soThuTu,
    //     ]);

    //     return response()->json($benhNhan, 201);
    // }

        // API đăng ký bệnh nhân & cấp số
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //        'mathe'    => 'nullable|string',
    //     'hoten'    => 'nullable|string',
    //     'ngaysinh' => 'nullable|string',
    //     'phankhu'  => 'required|integer',
    //     ]);

    //     $today = now()->toDateString();

    //     // Cấu hình mốc khởi tạo cho từng phân khu
    //     $baseNumbers = [
    //         91 => ['thuong' => 10000, 'uutien' => 0],
    //         92 => ['thuong' => 0,     'uutien' => 20000],
    //         93 => ['thuong' => 30000, 'uutien' => 0],
    //     ];

    //     // ✅ 1. Kiểm tra bệnh nhân trong ngày đã có chưa
    //     $benhNhan = BenhNhan::where('mathe', $data['mathe'])
    //         ->where('phankhu', $data['phankhu'])
    //         ->whereDate('created_at', $today)
    //         ->first();

    //     if ($benhNhan) {
    //         return response()->json($benhNhan);
    //     }

    //     // ✅ 2. Transaction an toàn
    //     return DB::transaction(function () use ($data, $today, $baseNumbers) {
    //         $base = $baseNumbers[$data['phankhu']] ?? ['thuong' => 0, 'uutien' => 0];

    //         // ✅ 3. Lấy sott của phân khu (nếu chưa có thì tạo với số khởi tạo)
    //         $sott = Sott::lockForUpdate()->firstOrCreate(
    //             ['phankhu' => $data['phankhu']],
    //             [
    //                 'sott_thuong' => $base['thuong'],
    //                 'sott_uutien' => $base['uutien'],
    //             ]
    //         );

    //         // ✅ 4. Reset nếu qua ngày mới
    //         if ($sott->updated_at->toDateString() !== $today) {
    //             $sott->update([
    //                 'sott_thuong' => $base['thuong'],
    //                 'sott_uutien' => $base['uutien'],
    //             ]);
    //             $sott->refresh();
    //         }

    //         // ✅ 5. Tăng số theo quy định
    //         if ($data['phankhu'] == 92) {
    //             $sott->increment('sott_uutien');
    //             $soThuTu = $sott->sott_uutien;
    //         } else {
    //             $sott->increment('sott_thuong');
    //             $soThuTu = $sott->sott_thuong;
    //         }

    //         // ✅ 6. Lưu thông tin bệnh nhân mới
    //         $benhNhan = BenhNhan::create([
    //             'mathe'    => $data['mathe'],
    //             'hoten'    => $data['hoten'],
    //             'ngaysinh' => $data['ngaysinh'],
    //             'phankhu'  => $data['phankhu'],
    //             'sott'     => $soThuTu,
    //         ]);

    //         return response()->json($benhNhan, 201);
    //     });
    // }

        // API đăng ký bệnh nhân & cấp số
    public function store(Request $request)
    {
        $data = $request->validate([
            'mathe'    => 'nullable|string',
            'hoten'    => 'nullable|string',
            'ngaysinh' => 'nullable|string',
            'phankhu'  => 'required|integer',
        ]);

        $today = now()->toDateString();

        // Cấu hình mốc khởi tạo cho từng phân khu
        $baseNumbers = [
            91 => ['thuong' => 10000, 'uutien' => 0],
            92 => ['thuong' => 0,     'uutien' => 20000],
            93 => ['thuong' => 30000, 'uutien' => 0],
        ];

        // ✅ 1. Kiểm tra bệnh nhân trong ngày đã có chưa (chỉ khi có mã thẻ)
        $benhNhan = null;
        if (!empty($data['mathe'])) {
            $benhNhan = BenhNhan::where('mathe', $data['mathe'])
                ->where('phankhu', $data['phankhu'])
                ->whereDate('created_at', $today)
                ->first();
        }

        if ($benhNhan) {
            return response()->json($benhNhan);
        }

        // ✅ 2. Transaction an toàn
        return DB::transaction(function () use ($data, $today, $baseNumbers) {
            $base = $baseNumbers[$data['phankhu']] ?? ['thuong' => 0, 'uutien' => 0];

            // ✅ 3. Lấy sott của phân khu (nếu chưa có thì tạo với số khởi tạo)
            $sott = Sott::lockForUpdate()->firstOrCreate(
                ['phankhu' => $data['phankhu']],
                [
                    'sott_thuong' => $base['thuong'],
                    'sott_uutien' => $base['uutien'],
                ]
            );

            // ✅ 4. Reset nếu qua ngày mới
            if ($sott->updated_at->toDateString() !== $today) {
                $sott->update([
                    'sott_thuong' => $base['thuong'],
                    'sott_uutien' => $base['uutien'],
                ]);
                $sott->refresh();
            }

            // ✅ 5. Tăng số theo quy định
            if ($data['phankhu'] == 92) {
                $sott->increment('sott_uutien');
                $soThuTu = $sott->sott_uutien;
            } else {
                $sott->increment('sott_thuong');
                $soThuTu = $sott->sott_thuong;
            }

            // ✅ 6. Lưu thông tin bệnh nhân mới (có thể rỗng mathe/hoten)
            $benhNhan = BenhNhan::create([
                'mathe'    => $data['mathe'] ?? '',
                'hoten'    => $data['hoten'] ?? '',
                'ngaysinh' => $data['ngaysinh'] ?? '',
                'phankhu'  => $data['phankhu'],
                'sott'     => $soThuTu,
            ]);

            return response()->json($benhNhan, 201);
        });
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


    // API lấy thông tin bệnh nhân theo sott + phankhu
    public function thongtin(Request $request)
    {
        $data = $request->validate([
            'sott'    => 'required|integer',
            'phankhu' => 'required|integer',
        ]);

        $today = now()->toDateString();

        $benhNhan = BenhNhan::where('sott', $data['sott'])
            ->where('phankhu', $data['phankhu'])
            ->whereDate('created_at', $today)
            ->first();

        if (!$benhNhan) {
            return response()->json([
                'message' => 'Không tìm thấy bệnh nhân',
            ], 404);
        }

        return response()->json([
            'id'       => $benhNhan->id,
            'hoten'    => $benhNhan->hoten,
            'ngaysinh' => $benhNhan->ngaysinh,
            'mathe'    => $benhNhan->mathe,
            'phankhu'  => $benhNhan->phankhu,
            'sott'     => $benhNhan->sott,
        ]);
    }



}
