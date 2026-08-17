<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Component;

class DashboardComponent extends Component
{
    use AttendanceDetailTrait;

    public function render()
    {
        /** @var Collection<Attendance>  */
        $attendances = Attendance::where('date', date('Y-m-d'))->get();

        /** @var Collection<User>  */
        $employees = User::where('group', 'user')
            ->paginate(20)
            ->through(function (User $user) use ($attendances) {
                return $user->setAttribute(
                    'attendance',
                    $attendances
                        ->where(fn (Attendance $attendance) => $attendance->user_id === $user->id)
                        ->first(),
                );
            });

        $employeesCount = User::where('group', 'user')->count();
        $presentCount = $attendances->where(fn ($attendance) => $attendance->status === 'present')->count();
        $lateCount = $attendances->where(fn ($attendance) => $attendance->status === 'late')->count();
        $excusedCount = $attendances->where(fn ($attendance) => $attendance->status === 'excused')->count();
        $sickCount = $attendances->where(fn ($attendance) => $attendance->status === 'sick')->count();
        $incompleteCount = $attendances->where(fn ($attendance) => $attendance->status === 'incomplete')->count();
        $absentCount = $employeesCount - ($presentCount + $lateCount + $excusedCount + $sickCount + $incompleteCount);
        $pendingLeaveCount = LeaveRequest::where('status', 'pending')->count();

        $isTodayHoliday = \App\Helpers::isHoliday(Carbon::now());
        $todayHoliday = Holiday::where('date', date('Y-m-d'))->first();
        $todayHolidayName = $isTodayHoliday ? ($todayHoliday?->name ?? __('Weekend')) : null;
        $upcomingHolidays = Holiday::where('date', '>', date('Y-m-d'))
            ->orderBy('date')
            ->limit(5)
            ->get();

        $last7Days = [];
        $labels7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels7Days[] = Carbon::parse($date)->translatedFormat('d M');
            $last7Days[] = Attendance::where('date', $date)
                ->whereIn('status', ['present', 'late'])
                ->count();
        }

        return view('livewire.admin.dashboard', [
            'employees' => $employees,
            'employeesCount' => $employeesCount,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'excusedCount' => $excusedCount,
            'sickCount' => $sickCount,
            'incompleteCount' => $incompleteCount,
            'absentCount' => $isTodayHoliday ? 0 : $absentCount,
            'pendingLeaveCount' => $pendingLeaveCount,
            'last7Days' => $last7Days,
            'labels7Days' => $labels7Days,
            'isTodayHoliday' => $isTodayHoliday,
            'todayHolidayName' => $todayHolidayName,
            'upcomingHolidays' => $upcomingHolidays,
        ]);
    }
}
