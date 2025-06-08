@extends('client.accounts.information')

@section('content-information')
    <div id="schedule" class="content-section">
        <h1>Thời Khóa Biểu</h1>
        <table>
            <tr>
                <th>Thời Gian</th>
                <th>Môn Học</th>
                <th>Giáo Viên</th>
                <th>Phòng</th>
            </tr>
            <tr>
                <td>8:00 - 9:30</td>
                <td>Toán</td>
                <td>Nguyễn Văn A</td>
                <td>A101</td>
            </tr>
            <tr>
                <td>9:45 - 11:15</td>
                <td>Văn</td>
                <td>Trần Thị B</td>
                <td>A102</td>
            </tr>
        </table>
    </div>
@endsection
