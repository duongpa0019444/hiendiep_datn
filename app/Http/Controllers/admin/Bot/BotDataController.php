<?php

namespace App\Http\Controllers\Admin\Bot;

use App\Http\Controllers\Controller;
use App\Models\TrainingData;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
class BotDataController extends Controller
{

    public function filter(Request $request, $id)
    {
        $query = TrainingData::where('training_set_id', $id)
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('question', 'like', '%' . $keyword . '%')
                    ->orWhere('keywords', 'like', '%' . $keyword . '%');
            });
        }


        $datas = $query->paginate($request->limit ?? 10);

        // Giữ lại tham số lọc khi phân trang
        $datas->appends($request->all());

        return response()->json([
            'datas'      => $datas,
            'pagination' => $datas->links('pagination::bootstrap-5')->toHtml(),
        ]);
    }



    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
