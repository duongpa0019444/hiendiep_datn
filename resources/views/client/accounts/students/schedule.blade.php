@extends('client.accounts.information')

@section('content-information')
    <div id="schedule" class="content-section">
        <h2>Thời Khóa Biểu</h2>
        <p>Danh sách các môn học và thời gian học của {{ Auth::user()->name }}.</p>

        @if ($schedules->isEmpty())
            <p>Không có lịch học nào.</p>
        @else
            <table>
                <tr>
                    <th>Thứ</th>
                    <th>Ngày</th>
                    <th>Thời Gian</th>
                    <th>Môn Học</th>
                    <th>Giáo Viên</th>
                </tr>
                @foreach ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->day_of_week }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td>{{ $schedule->course_name }}</td>
                        <td>{{ $schedule->teacher_name }}</td>
                    </tr>
                @endforeach
            </table>
            <div id="pagination-wrapper" class="flex-grow-1">
                {{ $schedules->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
