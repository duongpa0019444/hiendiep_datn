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
            $query->where('Capacity', $request->capacity);
        }
        // Lấy danh sách + phân trang
        $classrooms = $query->orderBy('id', 'desc')->paginate(10);
        $classrooms->appends($request->all());

        // ===== Thống kê =====
        $totalRooms = Classroom::count();
        $roomsInUse = 0;
        $roomsEmpty = 0;

        $now = now();

        foreach ($classrooms as $room) {
            if ($room->status == 0) {
                $room->status_text = 'Đang được sử dụng';
            } elseif ($room->status == 1) {
                $room->status_text = 'Chưa được sử dụng';
            }
        }

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
            'room_name'  => 'required|string|max:255|unique:class_room,room_name',
            'status'     => 'required|in:0,1',
            'Capacity'   => 'required|integer|min:1|max:100',
            'note'       => 'nullable|string',
            'class_id'   => 'nullable|exists:classes,id',
            'name_class' => 'nullable|string|max:255',
        ];

        $data = $request->validate($rules);

        // Nếu có class_id thì lấy thời gian từ schedules
        if (!empty($data['class_id'])) {
            $minTime = DB::table('schedules')->where('class_id', $data['class_id'])->min('start_time');
            $maxTime = DB::table('schedules')->where('class_id', $data['class_id'])->max('end_time');

            if (!$minTime || !$maxTime) {
                return back()->with('error', 'Lớp này chưa có lịch học, không thể gán phòng.');
            }

            $data['start_time'] = $minTime;
            $data['end_time']   = $maxTime;
        }


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

            // Nếu bạn vẫn muốn check thêm trạng thái
            if ((int)$classroom->status === 1) {
                return back()->with('error', 'Phòng đang được sử dụng, không thể xoá.');
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

   $query = Schedule::with([
            'class:id,name,status,courses_id',      // load lớp
            'class.course:id,name'                  // load khóa học của lớp
        ])
        ->where('room', $classroom->id);

    // ===== Bộ lọc =====

    // Lọc theo tên lớp
    if ($request->filled('name')) {
        $query->whereHas('class', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->name . '%'); 
        });
    }

    // Lọc theo ngày
    if ($request->filled('date')) {
        $query->whereDate('date', $request->date);
    }

    // Lọc theo giờ bắt đầu
    if ($request->filled('start_time')) {
        $query->where('start_time', '>=', $request->start_time);
    }

    // Lọc theo giờ kết thúc
    if ($request->filled('end_time')) {
        $query->where('end_time', '<=', $request->end_time);
    }

    $schedules = $query->orderBy('date', 'asc')->get();

    return view('admin.classroom.detail_room', compact('classroom', 'schedules'));
}


    // sửa phòng

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return view('admin.classroom.edit-room', compact('classroom'));
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);

        $rules = [
            'status'   => 'required|in:0,1',
            'Capacity' => 'required|integer|min:1|max:100',
        ];

        $data = $request->validate($rules);

        $classroom->update($data);

        return redirect()->route('admin.classroom.list-room')
            ->with('success', 'Cập nhật phòng học thành công!');
    }
}
