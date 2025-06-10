<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\quizzes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;

class quizzesController extends Controller
{
    //
    public function index(Request $request)
    {
        $quizzes = quizzes::orderByDesc('created_at')->with(['creator', 'course', 'class'])->paginate(10);
        $statistics = DB::select("
            SELECT
                (SELECT COUNT(*) FROM quizzes WHERE deleted_at IS NULL) AS total_quizzes,
                (SELECT COUNT(*) FROM quizzes WHERE is_public = 1 AND deleted_at IS NULL) AS total_public_quizzes,
                (SELECT COUNT(*) FROM quiz_attempts) AS total_attempts,
                (SELECT COUNT(user_id)
                FROM quiz_attempts
                WHERE user_id IN (SELECT id FROM users WHERE role = 'student')) AS total_students_participated
            FROM DUAL
        ");
        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'quizzes' => $quizzes,
                'pagination' => $quizzes->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.quizzes.quizzes-list', compact('quizzes', 'statistics'));
    }


    public function filter(Request $request)
    {
        $quizzes = $this->getFilterQuizzes($request);
        $quizzes->appends($request->all());
        return response()->json([
            'quizzes' => $quizzes,
            'pagination' => $quizzes->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterQuizzes(Request $request)
    {
        $query = quizzes::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('class', function ($q) use ($request) {
                $q->where('id', $request->class_id);
            });
        }

        if ($request->filled('course_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('id', $request->course_id);
            });
        }

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        return $query->with(['creator', 'course', 'class'])->orderByDesc('created_at')->paginate($request->limit);
    }

    public function delete($id)
    {
        $quiz = quizzes::findOrFail($id);
        $quiz->delete();
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'access_code' => 'nullable|string|max:20',
            'is_public' => 'required|boolean',
            'class_id' => 'nullable|integer|exists:classes,id',
            'course_id' => 'nullable|integer|exists:courses,id',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.string' => 'Tiêu đề phải là chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả không được để trống.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'duration_minutes.required' => 'Thời gian làm bài là bắt buộc.',
            'duration_minutes.integer' => 'Thời gian làm bài phải là số nguyên.',
            'duration_minutes.min' => 'Thời gian làm bài phải ít nhất là 1 phút.',
            'access_code.string' => 'Mã truy cập phải là chuỗi.',
            'access_code.max' => 'Mã truy cập không được vượt quá 20 ký tự.',
            'is_public.required' => 'Vui lòng chọn loại hiển thị của quiz.',
            'is_public.boolean' => 'Trường is_public phải là giá trị đúng hoặc sai.',
            'class_id.integer' => 'Lớp học không hợp lệ.',
            'class_id.exists' => 'Lớp học được chọn không tồn tại.',
            'course_id.integer' => 'Khóa học không hợp lệ.',
            'course_id.exists' => 'Khóa học được chọn không tồn tại.',
        ]);

        // Custom rule: nếu là "Không công khai" thì phải chọn class_id hoặc course_id
        $validator->after(function ($validator) use ($request) {
            if ($request->input('is_public') == 0) {
                if (empty($request->input('class_id')) && empty($request->input('course_id'))) {
                    $validator->errors()->add('class_id', 'Bạn phải chọn ít nhất lớp học hoặc khóa học nếu quiz là riêng tư.');
                    $validator->errors()->add('course_id', 'Bạn phải chọn ít nhất lớp học hoặc khóa học nếu quiz là riêng tư.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $quizz = quizzes::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'draft',
            'duration_minutes' => $validated['duration_minutes'],
            'access_code' => $validated['access_code'] ?? null,
            'is_public' => $validated['is_public'],
            'class_id' => $validated['is_public'] ? null : $validated['class_id'],
            'course_id' => $validated['is_public'] ? null : $validated['course_id'],
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm bài quiz thành công!',
            'quiz' => $quizz,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $quiz = quizzes::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'access_code' => 'nullable|string|max:20',
            'is_public' => 'required|boolean',
            'class_id' => 'nullable|integer|exists:classes,id',
            'course_id' => 'nullable|integer|exists:courses,id',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.string' => 'Tiêu đề phải là chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả không được để trống.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'duration_minutes.required' => 'Thời gian làm bài là bắt buộc.',
            'duration_minutes.integer' => 'Thời gian làm bài phải là số nguyên.',
            'duration_minutes.min' => 'Thời gian làm bài phải ít nhất là 1 phút.',
            'access_code.string' => 'Mã truy cập phải là chuỗi.',
            'access_code.max' => 'Mã truy cập không được vượt quá 20 ký tự.',
            'is_public.required' => 'Vui lòng chọn loại hiển thị của quiz.',
            'is_public.boolean' => 'Trường is_public phải là giá trị đúng hoặc sai.',
            'class_id.integer' => 'Lớp học không hợp lệ.',
            'class_id.exists' => 'Lớp học được chọn không tồn tại.',
            'course_id.integer' => 'Khóa học không hợp lệ.',
            'course_id.exists' => 'Khóa học được chọn không tồn tại.',
        ]);

        // Custom rule: nếu là "Không công khai" thì phải chọn class_id hoặc course_id
        $validator->after(function ($validator) use ($request) {
            if ($request->input('is_public') == 0) {
                if (empty($request->input('class_id')) && empty($request->input('course_id'))) {
                    $validator->errors()->add('class_id', 'Bạn phải chọn ít nhất lớp học hoặc khóa học nếu quiz là riêng tư.');
                    $validator->errors()->add('course_id', 'Bạn phải chọn ít nhất lớp học hoặc khóa học nếu quiz là riêng tư.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'access_code' => $validated['access_code'],
            'is_public' => $validated['is_public'],
            'class_id' => $validated['is_public'] ? null : $validated['class_id'],
            'course_id' => $validated['is_public'] ? null : $validated['course_id'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'action' => 'edit',
            'message' => 'Cập nhật bài quiz thành công!',
            'quiz' => $quiz,
        ], 200);
    }




    public function detail($id, Request $request)
    {
        $quiz = quizzes::with(['creator', 'course', 'class'])->findOrFail($id);

        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json($quiz);
        }

        return view('admin.quizzes.quizzes-detail', compact('quiz'));
    }
}
