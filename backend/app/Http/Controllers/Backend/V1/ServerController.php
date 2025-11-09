<?php

namespace App\Http\Controllers\Backend\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\StoreServerRequest;
use App\Http\Requests\Servers\UpdateServerRequest;
use App\Http\Resources\ServerResource;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $servers = Server::orderBy('id', 'desc')->get();

    return response()->json([
        'success' => true,
        'message' => 'Danh sách máy chủ',
        'data'    => ServerResource::collection($servers),
    ], 200);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServerRequest $request)
{
    $validated = $request->validated();

    try {
        DB::beginTransaction();

        $slug = Str::slug($validated['name']);
        if (Server::where('slug', $slug)->exists()) {
            $slug .= '-' . uniqid();
        }

        $server = Server::create([
            'name' => trim($validated['name']),
            'slug' => $slug,
            'description' => $validated['description'] ?? '',
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Tạo máy chủ thành công',
            'data'    => new ServerResource($server),
        ], 201);
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Lỗi khi tạo server', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi tạo máy chủ',
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $server = Server::find($id);

    if (!$server) {
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy máy chủ',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Chi tiết máy chủ',
        'data'    => new ServerResource($server),
    ], 200);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServerRequest $request, $id)
{
    $server = Server::find($id);
    if (!$server) {
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy máy chủ',
        ], 404);
    }

    $validated = $request->validated();

    try {
        DB::beginTransaction();

        $slug = Str::slug($validated['name']);
        if ($slug !== $server->slug) {
            if (Server::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug .= '-' . uniqid();
            }
            $server->slug = $slug;
        }

        $server->update([
            'name' => trim($validated['name']),
            'description' => $validated['description'] ?? '',
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật máy chủ thành công',
            'data'    => new ServerResource($server),
        ], 200);
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Lỗi khi cập nhật server', ['error' => $e->getMessage()]);

        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi cập nhật.',
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $server = Server::find($id);

    if (!$server) {
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy máy chủ để xóa',
        ], 404);
    }

    try {
        $server->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa máy chủ thành công',
        ], 200);
    } catch (\Throwable $e) {
        Log::error('Lỗi khi xóa server', ['error' => $e->getMessage()]);

        return response()->json([
            'success' => false,
            'message' => 'Không thể xóa máy chủ lúc này.',
        ], 500);
    }
}

}
