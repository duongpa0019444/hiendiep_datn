<?php

namespace App\Http\Controllers\Admin\Bot;

use App\Http\Controllers\Controller;
use App\Models\TrainingData;
use App\Models\TrainingDocument;
use App\Models\TrainingSet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    //
    public function index(Request $request)
    {
        $trainingSets = TrainingSet::withCount(['documents', 'questions'])->latest()->paginate(6);
        $statistics = DB::table('bot_training_sets as bts')
            ->leftJoin('bot_documents as bd', 'bd.training_set_id', '=', 'bts.id')
            ->leftJoin('bot_training_data as btd', 'btd.training_set_id', '=', 'bts.id')
            ->selectRaw('
                COUNT(DISTINCT bts.id) AS tong_bo_training,
                COUNT(DISTINCT bd.id) AS tong_tai_lieu,
                COUNT(DISTINCT btd.id) AS tong_cau_hoi,
                MAX(bts.updated_at) AS cap_nhat_gan_nhat
            ')->first();

        $statistics->cap_nhat_gan_nhat = Carbon::parse($statistics->cap_nhat_gan_nhat);
        if ($request->ajax()) {
            return response()->json([
                'trainingSets' => $trainingSets,
                'pagination' => $trainingSets->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.bot.training-list', compact('trainingSets', 'statistics'));
    }

    public function filter(Request $request)
    {
        $trainingSets = $this->getFilter($request);

        // Giữ lại tham số lọc khi phân trang
        $trainingSets->appends($request->all());

        return response()->json([
            'trainingSets' => $trainingSets,
            'pagination' => $trainingSets->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilter(Request $request)
    {
        $query = TrainingSet::withCount(['documents', 'questions']);

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }
        switch ($request->filter) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'most_documents':
                $query->orderBy('documents_count', 'desc');
                break;

            case 'most_questions':
                $query->orderBy('questions_count', 'desc');
                break;

            default: // tất cả hoặc không có filter
                $query->latest('updated_at');
                break;
        }

        return $query->paginate($request->limit ?? 10);
    }



    public function storeSet(Request $request)
    {
        try {
            // Validate dữ liệu
            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
            ], [
                'name.required' => 'Tên bộ training là bắt buộc.',
                'name.string'   => 'Tên bộ training phải là chuỗi.',
                'name.max'      => 'Tên bộ training không được vượt quá 255 ký tự.',
                'description.string' => 'Mô tả phải là chuỗi.',
            ]);

            // Nếu validate fail → trả về lỗi
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Tạo mới training set
            $trainingSet = TrainingSet::create($validator->validated());

            return response()->json([
                'trainingSet' => $trainingSet,
                'success'     => 'Thêm mới thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Lỗi! Không thể thêm mới!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }




    public function updateSet(Request $request, $id)
    {
        try {
            // Tự tạo validator để trả lỗi về JSON
            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
            ], [
                'name.required' => 'Tên bộ training là bắt buộc.',
                'name.string'   => 'Tên bộ training phải là chuỗi.',
                'name.max'      => 'Tên bộ training không được vượt quá 255 ký tự.',
                'description.string' => 'Mô tả phải là chuỗi.',
            ]);

            // Nếu validate fail → trả về lỗi
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Tìm training set
            $trainingSet = TrainingSet::findOrFail($id);

            // Cập nhật
            $trainingSet->update($validator->validated());

            return response()->json([
                'trainingSet' => $trainingSet,
                'success'     => 'Cập nhật thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Lỗi! Không thể cập nhật!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteSet($id)
    {
        try {
            $trainingSet = TrainingSet::findOrFail($id);
            $trainingSet->delete();

            return response()->json([
                'success' => 'Xóa thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Lỗi! Không thể xóa!',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function detail(Request $request , $id)
    {
        // Lấy bộ training
        $training = TrainingSet::findOrFail($id);

        // Lấy danh sách Q&A trong bộ training
        $data = TrainingData::where('training_set_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Lấy danh sách tài liệu trong bộ training
        $documents = TrainingDocument::where('training_set_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'data' => $data,
                'document' => $documents,
                'pagination-data' => $data->links('pagination::bootstrap-5')->toHtml(),
                'pagination-document' => $documents->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        // Trả về view
        return view('admin.bot.training-detail', compact('training', 'data', 'documents'));
    }
}
