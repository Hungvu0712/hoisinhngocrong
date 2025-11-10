<?php

namespace App\Http\Controllers\Backend\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Planets\StorePlanetRequest;
use App\Http\Requests\Planets\UpdatePlanetRequest;
use App\Http\Resources\PlanetResource;
use App\Models\Planet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlanetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planets = Planet::orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Danh sách hành tinh',
            'data' => PlanetResource::collection($planets), // để trả ra 1 mảng json nhìn đẹp hơn được viết bên PlanetResource
        ], 200); // 200 là trạng thái ok
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanetRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction(); // 1 thao tác giao dịch mới

            $slug = Str::slug($validated['name']);  // tạo slug tự động dựa trên name
            if(Planet::where('slug', $slug)->exists()){ // tìm trong bàng planets xem có slug này có chưa
                $slug .= '-' . uniqid(); // dấu gạch nối -, uniqid() tạo chuỗi ID duy nhất
            }

            $planet = Planet::create([
                'name' => trim($validated['name']),
                'slug' => $slug,
                'description' => $validated['description'] ?? '',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tạo hành tinh thành công',
                'data' => new PlanetResource($planet),
            ], 201);
        }catch(\Throwable $e){
            DB::rollback();
            Log::error('Lỗi khi khởi tạo', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể khởi tạo hành tinh',
            ], 500); // trạng thái 500 lỗi server nội bộ
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $planet = Planet::find($id);

        if($planet){
            return response()->json([
                'success' => false,
                'message' => 'Lỗi không tìm thấy hành tinh',
        ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Chi tiết hành tinh',
            'data' => new PlanetResource($planet),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanetRequest $request, string $id)
    {
        $planet = Planet::find($id);

        if (!$planet) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hành tinh',
            ], 404);
        }

        $validated = $request->validated();
        try{
            DB::beginTransaction();

            $slug = Str::slug($validated['name']);
            if($slug !== $planet->slug){
                if(Planet::where('slug', $slug)->where('id', '!=', $id)->exists()){
                    $slug .= '-' . uniqid();
                }
                $planet->slug = $slug;
            }

            $planet->update([
                'name' => trim($validated['name']),
                'description' => $validated['description'] ?? '',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết hành tinh',
                'data' => new PlanetResource($planet),
            ], 200);
        }catch(\Throwable $e){
            DB::rollBack();
            Log::error('Lỗi khi khởi tạo', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể khởi tạo hành tinh',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $planet = Planet::find($id);

        if (!$planet) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy hành tinh',
            ], 404);
        }

        try{
            $planet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa hành tinh',
            ], 200);
        }catch(\Throwable $e){
            Log::error('Lỗi khi xóa hành tinh', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa hành tinh',
            ], 500);
        }
    }
}
