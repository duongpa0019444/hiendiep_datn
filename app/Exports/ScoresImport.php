<?php

namespace App\Exports;

use App\Http\Controllers\admin\ScoreController;
use App\Models\ClassStudent;
use App\Models\Classes;
use App\Models\Score;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ScoresImport implements ToCollection
{


    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Bỏ dòng tiêu đề

            $studentName     = trim($row[0]);
            $studentBirthday = ScoreController::parseExcelDate($row[1]);
            $className       = $row[2];
            $scoreType       = strtolower(trim($row[3]));
            $scoreValue      = floatval($row[4]);
            $examDate        = ScoreController::parseExcelDate($row[5]);


            // dd($studentName, $studentBirthday, $className, $scoreType, $scoreValue, $examDate);

            $student = User::where('name', $studentName)
                ->whereDate('birth_date', $studentBirthday)
                ->first();
            // dd($student);
            if (!$student) {
                Log::warning("Không tìm thấy sinh viên: {$studentName} ({$studentBirthday})");
                continue;
            }

            $class = Classes::where('name', $className)->first();

            if (!$class) {
                Log::warning("Không tìm thấy lớp: {$className}");
                continue;
            }

            $studentId = $student->id;
            $classId = $class->id;

            $inClass = ClassStudent::where('student_id', $studentId)
                ->where('class_id', $classId)
                ->exists();

            if (!$inClass) {
                Log::warning("Học sinh '{$studentName}' không thuộc lớp '{$className}'");
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
                Score::create([
                    'student_id' => $studentId,
                    'class_id'   => $classId,
                    'score_type' => $scoreType,
                    'score'      => $scoreValue,
                    'exam_date'  => $examDate,
                ]);
            }
        }
    }
}
