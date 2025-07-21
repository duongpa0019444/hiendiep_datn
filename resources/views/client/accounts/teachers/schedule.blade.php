@extends('client.accounts.information')

@section('content-information')
    <div id="schedule" class="content-section">
        <h2>Lịch dạy</h2>

        @if ($schedules->isEmpty())
            <p>Không có lịch học nào.</p>
        @else
            <table>
                <tr>
                    <th>Thứ</th>
                    <th>Ngày</th>
                    <th>Thời Gian</th>
                    <th>Môn Học</th>
                    <th>Tác vụ</th>
                </tr>
                @foreach ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->day_of_week }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td>{{ $schedule->course_name }}</td>
                        <td>
                            <button class="btn btn-success btn-sm attendance-btn shadow-sm" 
                                        data-schedule-id="{{ $schedule->schedule_id }}"
                                        onclick="markAttendance({{ $schedule->schedule_id }})">
                                    <i class="icofont-check-circled" style="margin-right: 0.25rem;"></i>Điểm danh
                                </button>
                        </td>
                    </tr>
                @endforeach
            </table>
            <div id="pagination-wrapper" class="flex-grow-1">
                {{ $schedules->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
