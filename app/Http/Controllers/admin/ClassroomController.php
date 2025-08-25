<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\classes; // Model tương ứng với bảng classes
use App\Models\Schedule; // Model tương ứng với bảng classes
use Illuminate\Support\Facades\DB;



class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Classroom::query();

        // Lọc theo tên phòng
        if ($request->filled('room_name')) {
            $query->where('room_name', 'like', '%' . $request->room_name . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo sức chứa
        if ($request->filled('capacity')) {
            $query->where('capacity', $request->capacity);
        }

        // Lấy danh sách + phân trang
        $classrooms = $query
            ->withCount(['classes as classes_count' => function ($q) {
                $q->select(DB::raw('count(distinct classes.id)'))
                    ->where('classes.status', 'in_progress');
            }])
            ->orderBy('id', 'desc')
             ->paginate($request->limit ?? 10);



        $classrooms->appends($request->all());
        // dd($classrooms->all());
        // ===== Thống kê =====
        $totalRooms = Classroom::count();
        $roomsInUse = Classroom::where('status', 1)->count();
        $roomsEmpty = Classroom::where('status', 0)->count();

        return view('admin.classroom.list-room', compact(
            'classrooms',
            'totalRooms',
            'roomsInUse',
            'roomsEmpty'
        ));
    }

    public function getClassTimes($classId)
    {
        $schedule = Schedule::where('class_id', $classId)
            ->orderBy('date', 'asc')
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'Lớp này chưa có lịch học']);
        }

        return response()->json([
            'date'       => $schedule->date,
            'start_time' => $schedule->start_time,
            'end_time'   => $schedule->end_time
        ]);
    }

    public function create()
    {
        $classes = classes::all(); // Model ClassModel tương ứng bảng classes
        return view('admin.classroom.create-room', compact('classes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'room_name' => 'required|string|max:255|unique:class_room,room_name',
            'status'    => 'required|in:0,1',
            'capacity'  => 'required|integer|min:1|max:100',
            'note'      => 'nullable|string|max:500',
        ];

        $messages = [
            'room_name.required' => 'Tên phòng không được để trống.',
            'room_name.string'   => 'Tên phòng phải là chuỗi ký tự.',
            'room_name.max'      => 'Tên phòng không được vượt quá 255 ký tự.',
            'room_name.unique'   => 'Tên phòng này đã tồn tại.',

            'status.required'    => 'Trạng thái là bắt buộc.',
            'status.in'          => 'Trạng thái chỉ được chọn 0 (không hoạt động) hoặc 1 (hoạt động).',

            'capacity.required'  => 'Sức chứa là bắt buộc.',
            'capacity.integer'   => 'Sức chứa phải là số nguyên.',
            'capacity.min'       => 'Sức chứa tối thiểu là 1.',
            'capacity.max'       => 'Sức chứa tối đa là 100.',

            'note.string'        => 'Ghi chú phải là chuỗi ký tự.',
            'note.max'           => 'Ghi chú không được vượt quá 500 ký tự.',
        ];

        $data = $request->validate($rules, $messages);

        Classroom::create($data);

        return redirect()->route('admin.classroom.list-room')
            ->with('success', 'Thêm phòng học thành công!');
    }

    //
    public function listRoom()
    {
        session()->forget('_old_input'); // Xóa dữ liệu cũ của form
        return view('admin.classroom.list-room');
    }

    public function delete($id)
    {
        try {
            $classroom = Classroom::findOrFail($id);

            // Kiểm tra xem phòng có lớp nào đã xếp chưa
            $hasSchedules = Schedule::where('room', $classroom->room_name)->exists();
            if ($hasSchedules) {
                return back()->with('error', 'Phòng "' . $classroom->room_name . '" đã có lớp học, không thể xoá.');
            }

            $classroom->delete();

            return redirect()
                ->route('admin.classroom.list-room')
                ->with('success', 'Xoá phòng học thành công!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Không xoá được phòng học: ' . $e->getMessage());
        }
    }

    public function detailRoom(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $query = \App\Models\Classes::with('course:id,name')
            ->whereHas('schedules', function ($q) use ($id, $request) {
                $q->where('room', $id);
            });

        $allClasses = $query->pluck('name', 'id');

        // Lọc theo tên lớp
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Ưu tiên lớp đang học lên đầu
        // Lấy danh sách lớp (distinct để tránh trùng) + phân trang
        $classes = $query->distinct()
            ->orderByRaw("CASE
            WHEN status = 'in_progress' THEN 1
            WHEN status = 'not_started' THEN 2
            WHEN status = 'completed' THEN 3
            ELSE 4
          END")
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 9) // số lượng item mỗi trang
            ->withQueryString(); // giữ lại query filter khi phân trang

        return view('admin.classroom.detail_room', compact('classroom', 'classes', 'allClasses'));
    }


    // sửa phòng

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return view('admin.classroom.edit-room', compact('classroom'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $classroom = Classroom::findOrFail($id);

        $rules = [
            'room_name' => 'required|string|max:255|unique:class_room,room_name,' . $id,
            'status'    => 'required|in:0,1',
            'capacity'  => 'required|integer|min:1|max:100',
            'note'      => 'nullable|string|max:500',
        ];

        $messages = [
            'room_name.required' => 'Tên phòng không được để trống.',
            'room_name.string'   => 'Tên phòng phải là chuỗi ký tự.',
            'room_name.max'      => 'Tên phòng không được vượt quá 255 ký tự.',
            'room_name.unique'   => 'Tên phòng này đã tồn tại.',

            'status.required'    => 'Trạng thái là bắt buộc.',
            'status.in'          => 'Trạng thái chỉ được chọn 0 (không hoạt động) hoặc 1 (hoạt động).',

            'capacity.required'  => 'Sức chứa là bắt buộc.',
            'capacity.integer'   => 'Sức chứa phải là số nguyên.',
            'capacity.min'       => 'Sức chứa tối thiểu là 1.',
            'capacity.max'       => 'Sức chứa tối đa là 100.',

            'note.string'        => 'Ghi chú phải là chuỗi ký tự.',
            'note.max'           => 'Ghi chú không được vượt quá 500 ký tự.',
        ];

        $data = $request->validate($rules, $messages);


        $classroom->update($data);

        return redirect()->route('admin.classroom.list-room')
            ->with('success', 'Cập nhật phòng học thành công!');
    }
}
