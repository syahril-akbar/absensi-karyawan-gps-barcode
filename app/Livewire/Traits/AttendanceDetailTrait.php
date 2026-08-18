<?php

namespace App\Livewire\Traits;

use App\Models\Attendance;

trait AttendanceDetailTrait
{
    public bool $showDetail = false;
    public $currentAttendance = [];

    public function show($attendanceId)
    {
        /** @var Attendance */
        // Check if it's a date string (Y-m-d) for holiday
        if (is_string($attendanceId) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $attendanceId)) {
            $this->showDetail = true;
            $this->currentAttendance = [
                'status' => 'holiday',
                'date' => $attendanceId,
                'name' => auth()->user()->name,
                'nip' => auth()->user()->nip,
            ];
            return;
        }

        $attendance = Attendance::find($attendanceId);
        if ($attendance) {
            $this->showDetail = true;
            $this->currentAttendance = $attendance->getAttributes();
            $this->currentAttendance['name'] = $attendance->user->name;
            $this->currentAttendance['nip'] = $attendance->user->nip;
            $this->currentAttendance['address'] = $attendance->user->address;
            if ($attendance->attachment) {
                $this->currentAttendance['attachment'] = $attendance->attachment_url;
            }
            if ($attendance->barcode_id) {
                $this->currentAttendance['barcode'] = $attendance->barcode;
            }
            if ($attendance->shift_id) {
                $this->currentAttendance['shift'] = $attendance->shift;
            }
        }
    }
}
