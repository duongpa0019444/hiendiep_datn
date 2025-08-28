<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        // vai trò
        $userRole = Auth::user()->role;
        // nhiệm vụ của nhân viên
        $userMission = Auth::user()->mission;

        // lấy route
        $currentRoute = request()->route()->getName();


        if (!in_array($userRole, $roles)) {
            return redirect('/');
        }

        if ($userRole === 'staff') {
            if ($userMission === 'train') {
                $trainAllowedRoutes = [
                    // dashboard
                    'admin.dashboard',
                    'admin.dashboard.renderOverview',
                    'admin.dashboard.schedules.views',
                    'admin.dashboard.schedules.date',
                    'admin.chartRevenue',
                    'admin.revenueCourse',
                    'admin.dashboard.chart',

                    //Thống kê đâò tạo
                    'admin.thongke.daotao',
                    'admin.thongke.hocinhdangky',
                    'admin.thongke.buoiday',
                    'admin.thongke.statusClasses',
                    'admin.thongke.classStudentCounts',
                    'admin.thongke.classAverageScores',



                    // người dùng
                    'admin.account',
                    'admin.account.add',
                    // 'admin.account.check',
                    'admin.account.detail',
                    'admin.account.edit',
                    'admin.account.list',
                    'admin.account.schedule',
                    'admin.account.search',
                    'admin.account.store',
                    'admin.account.update',

                    // điểm danh
                    'admin.attendance.class',
                    'admin.attendance.getSchedules',
                    'admin.attendance.index',
                    'admin.attendance.getSchedulesByClass',
                    'admin.attendance.getClassDetail',
                    'admin.attendance.summary.update',
                    'admin.attendance.save',

                    // Lớp học
                    'admin.classes.add-student',
                    'admin.classes.detail',
                    'admin.classes.edit',
                    'admin.classes.force-delete-student',
                    'admin.classes.index',
                    'admin.classes.remove-student',
                    'admin.classes.restore-student',
                    'admin.classes.schedules',
                    'admin.classes.show',
                    'admin.classes.store',
                    'admin.classes.students',
                    'admin.classes.toggle-status',
                    'admin.classes.update',

                    // khóa học
                    'admin.course-list',
                    'admin.course-detail',
                    'admin.course-edit',
                    'admin.course-update',
                    'admin.course-delete',
                    'admin.course-add',
                    'admin.course-create',


                    // bài giảng
                    'admin.lession-add',
                    'admin.lession-create',
                    'admin.lession-edit',
                    'admin.lession-update',
                    'admin.lession-delete',

                    //Quản lý phòng học
                    'admin.classroom.list-room',
                    'admin.classroom.create',
                    'admin.classroom.store',
                    'admin.classroom.delete',
                    'admin.classroom.detail-room',
                    'admin.classroom.edit',
                    'admin.classroom.update',


                    // thông báo vẫn cho xóa vì ko sửa được
                    'admin.notifications',
                    'admin.notifications.filter',
                    'admin.notifications.seed',
                    'admin.notifications.delete',




                    // câu hỏi
                    'admin.questions.delete',
                    'admin.questions.edit',
                    'admin.questions.sentence.delete',
                    'admin.questions.sentence.edit',
                    'admin.questions.sentence.store',
                    'admin.questions.sentence.update',
                    'admin.questions.store',
                    'admin.questions.update',

                    // qiuz
                    'admin.quizz',
                    'admin.quizzes.detail',
                    'admin.quizzes.filter',
                    'admin.quizzes.results',
                    'admin.quizzes.results.class',
                    'admin.quizzes.results.class.student',
                    'admin.quizzes.results.class.student.attempts',
                    'admin.quizzes.results.class.student.filter',
                    'admin.quizzes.store',
                    'admin.quizzes.update',
                    'admin.quizzes.update.status',

                    // điểm số
                    'admin.score',
                    'admin.score.add',
                    'admin.score.detail',
                    'admin.score.detailSearch',
                    'admin.score.download',
                    'admin.score.edit',
                    'admin.score.search',
                    'admin.score.store',
                    'admin.score.update',
                    'admin.scores.export',
                    'admin.scores.import',

                    // lịch học
                    // Quản lý lịch học
                    'admin.schedules.index',
                    'admin.schedules.create',
                    'admin.schedules.store',
                    'admin.schedules.store-single',
                    'admin.schedules.edit',
                    'admin.schedules.update',
                    'admin.schedules.destroy',
                    'admin.schedules.restore',
                    'admin.schedules.students',
                    'admin.schedules.add-student',
                    'admin.schedules.remove-student',
                    'admin.classes.show',

                    // chủ đề
                    'admin.topics.create',
                    'admin.topics.edit',
                    'admin.topics.delete',
                    'admin.topics.filter',
                    'admin.topics.index',
                    'admin.topics.store',
                    'admin.topics.update',
                    'admin.topics.trash.filter',
                    'admin.topics.forceDelete',
                    'admin.topics.restore',
                    'admin.topics.trash',

                    // tin tức
                    'admin.news.create',
                    'admin.news.edit',
                    'admin.news.delete',
                    'admin.news.filter',
                    'admin.news.index',
                    'admin.news.store',
                    'admin.news.temp-upload',
                    'admin.news.update',
                    'admin.news.trash.filter',
                    'admin.news.forceDelete',
                    'admin.news.restore',
                    'admin.news.trash',

                    // lien he
                    'admin.contact',
                    'admin.contactDetail',
                    'contact.approve',
                    'contact.reject',
                    'contact.delete',

                    //Quản lý phòng học
                    'admin.classroom.list-room',
                    'admin.classroom.create',
                    'admin.classroom.store',
                    'admin.classroom.delete',
                    'admin.classroom.detail-room',
                    'admin.classroom.edit',
                    'admin.classroom.update'
                ];


                // kiểm tra nếu truy cập đường dẫn ko đc phân quyền
                if (!in_array($currentRoute, $trainAllowedRoutes)) {
                    Auth::logout();
                    return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập thao tác này!');
                }
            } elseif ($userMission === 'accountant') {
                $accountantAllowedRoutes = [

                    // dashboard
                    'admin.dashboard',
                    'admin.dashboard.renderOverview',
                    'admin.dashboard.schedules.views',
                    'admin.dashboard.schedules.date',
                    'admin.chartRevenue',
                    'admin.revenueCourse',
                    'admin.dashboard.chart',

                    //Thống kê tài chính
                    'admin.statistics.finance.tong-quy-luong',
                    'admin.statistics.finance.tong-doanh-thu',
                    'admin.statistics.finance.hoc-phi-lop',
                    'admin.statistics.finance.lai-lo',
                    'admin.thongke.taichinh',

                    //Xuất báo cáo
                    'admin.statistics.finance.tong-quy-luong.xuatExcel',
                    'admin.statistics.finance.tong-doanh-thu.xuatExcel',
                    'admin.statistics.finance.hoc-phi-lop.xuatExcel',
                    'admin.statistics.finance.lai-lo.xuatExcel',


                    // học phí
                    'admin.course_payments',
                    'admin.course_payments.detail',
                    'admin.course_payments.download',
                    'admin.course_payments.export',
                    'admin.course_payments.filter',
                    'admin.course_payments.show',
                    'admin.course_payments.statistics',
                    'admin.course_payments.update',
                    'admin.course_payments.trash',
                    'admin.course_payments.trash.filter',

                    // thông báo học phí
                    'admin.noti.coursepayment',
                    'admin.noti.coursepayment.filter',
                    'admin.noti.detail',

                    // thông báo vẫn cho xóa vì ko sửa được
                    'admin.notifications',
                    'admin.notifications.filter',
                    'admin.notifications.seed',
                    'admin.notifications.delete',

                    // lương giáo viên ko đc mở khóa chốt bảng lương
                    'admin.teacher_salaries',
                    'admin.teacher_salaries.data',
                    'admin.teacher_salaries.save',
                    'admin.teacher_salaries.upload',
                    'admin.teacher_salaries.filter',
                    'admin.teacher_salary_rules.details',
                    'admin.teacher_salaries.detail',
                    'admin.teacher_salary.store',
                    'admin.teacher-salary-rules.byTeacher',
                    'admin.teacher-salary-rules.searchTeacher',
                    'admin.teacher_salaries.lock',

                    // lương nhân viên ko đc mở khóa chốt bảng lương
                    'admin.staff_salaries',
                    'admin.staff_salaries.data',
                    'admin.staff_salaries.save',
                    'admin.staff_salaries.upload',
                    'admin.staff_salaries.filter',
                    'admin.staff_salaries.lock',
                    // 'admin.staff_salaries.unlock',
                    'admin.staff_salary_rules.details',
                    'admin.staff_salaries.detail',
                    'admin.staff_salary.store',
                    'admin.staff_salary_rules.bystaff',
                    'admin.staff-salary-rules.searchTeacher',


                    // chủ đề
                    'admin.topics.create',
                    'admin.topics.edit',
                    'admin.topics.delete',
                    'admin.topics.filter',
                    'admin.topics.index',
                    'admin.topics.store',
                    'admin.topics.update',
                    'admin.topics.trash.filter',
                    'admin.topics.forceDelete',
                    'admin.topics.restore',
                    'admin.topics.trash',

                    // tin tức
                    'admin.news.create',
                    'admin.news.edit',
                    'admin.news.delete',
                    'admin.news.filter',
                    'admin.news.index',
                    'admin.news.store',
                    'admin.news.temp-upload',
                    'admin.news.update',
                    'admin.news.trash.filter',
                    'admin.news.forceDelete',
                    'admin.news.restore',
                    'admin.news.trash',

                    // lien he
                    'admin.contact',
                    'admin.contactDetail',
                    'contact.approve',
                    'contact.reject',
                    'contact.delete'
                ];

                // kiểm tra nếu truy cập đường dẫn ko đc phân quyền
                if (!in_array($currentRoute, $accountantAllowedRoutes)) {
                    Auth::logout();
                    return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập thao tác này!');
                }
                return $next($request);
            } else {
                return redirect()->route('home')->with('success', 'Tài khoản của bạn chưa được phân nhiệm vụ quản lí!');
            }
        } else {
            return $next($request);
        }

        return $next($request);
    }
}
