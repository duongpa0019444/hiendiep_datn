<?php

namespace App\Exports;

use App\Http\Controllers\admin\ScoreController;
use App\Models\ClassStudent;
use App\Models\Classes;
use App\Models\courses;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ScoresImport implements ToCollection
{

    protected array $errors;

    public function __construct(array &$errors)
    {
        $this->errors = &$errors;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Bỏ dòng tiêu đề
            $rowNumber = $index + 1; // dòng thật trong Excel
            // Lấy dữ liệu từ từng cột
            $maSV            = trim($row[0]);
            $studentName     = trim($row[1]);
            $className       = trim($row[2]);
            $courseName      = trim($row[3]);
            $scoreType       = strtolower(trim($row[4]));
            $scoreValue  = $row[5] ?? null;
            $examDate        = ScoreController::parseExcelDate($row[6]);


            // dd($studentName, $studentBirthday, $className, $scoreType, $scoreValue, $examDate);

            // ==== Validate dữ liệu trống ====
            if (
                $maSV === '' || $studentName === '' || $className === '' ||
                $courseName === '' || $scoreType === '' || $scoreValue === null || $examDate === null
            ) {

                $this->errors[] = "❌ Lỗi dòng {$rowNumber}: Thiếu dữ liệu bắt buộc.";
                Log::warning("Dòng {$rowNumber}: dữ liệu không đầy đủ.", compact(
                    'maSV',
                    'studentName',
                    'className',
                    'courseName',
                    'scoreType',
                    'scoreValue',
                    'examDate'
                ));
                continue;
            }

            $student = User::where('name', $studentName)
                ->orWhere('snake_case', $maSV)
                ->first();
            // dd($student);
            if (!$student) {
                $this->errors[] = "Không tìm thấy tên sinh viên nàn dòng {$rowNumber}.";
                Log::warning("Không tìm thấy sinh viên: {$studentName} ({$maSV})");
                continue;
            }

            $class = Classes::where('name', $className)->first();

            if (!$class) {
                $this->errors[] = "Không tìm thấy tên lớp này dòng {$rowNumber}.";
                Log::warning("Không tìm thấy lớp: {$className}");
                continue;
            }

            $studentId = $student->id;
            $classId = $class->id;

            $inClass = ClassStudent::where('student_id', $studentId)
                ->where('class_id', $classId)
                ->exists();

            if (!$inClass) {
                $this->errors[] = "Không tìm thấy học sinh viên trong lớp này dòng {$rowNumber}.";
                Log::warning("Học sinh '{$studentName}' không thuộc lớp '{$className}'");
                continue;
            }

            $inCourse = courses::join('classes', 'classes.courses_id', '=', 'courses.id')
                ->join('class_student', 'class_student.class_id', '=', 'classes.id')
                ->where('courses.name', $courseName)
                ->where('class_student.student_id', $studentId)
                ->exists();
            if (!$inCourse) {
                $this->errors[] = "Không tìm thấy học sinh viên trong khóa học này dòng {$rowNumber}.";
                Log::warning("Học sinh '{$studentName}' không thuộc khóa học '{$courseName}'");
                continue;
            }

            $existing = Score::where([
                'student_id' => $studentId,
                'class_id'   => $classId,
                'score_type' => $scoreType,
            ])->first();

            // nếu đã tồn tại điểm thì đè lên điểm cũ
            if ($existing) {
                $existing->update([
                    'score'     => $scoreValue,
                    'exam_date' => $examDate,
                ]);
            } else {
                $sclassNameIP = Score::create([
                    'student_id' => $studentId,
                    'class_id'   => $classId,
                    'score_type' => $scoreType ?? '',
                    'score'      => $scoreValue ?? '',
                    'exam_date'  => $examDate ?? '',
                ]);
                return $sclassNameIP;
            }
        }
    }
}
