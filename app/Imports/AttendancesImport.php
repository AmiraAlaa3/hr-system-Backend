<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Attendnce;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class AttendancesImport implements ToCollection,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
{
    foreach ($rows as $row)
    {
        $attendance = Attendnce::where('employee_id', $row['employee_id'])
                                ->where('date', $this->convertDate($row['date']))
                                ->first();
        if($attendance){
            $attendance->update([
                'employee_id' => $row['employee_id'],
                'checkIN' => $this->convertTime($row['checkin']),
                'checkOUT' => $this->convertTime($row['checkout']),
                'date' => $this->convertDate($row['date']),
            ]);
        }
        else{
            Attendnce::create([
                'employee_id' => $row['employee_id'],
                'checkIN' => $this->convertTime($row['checkin']),
                'checkOUT' => $this->convertTime($row['checkout']),
                'date' => $this->convertDate($row['date']),
            ]);
        }
    }
}

private function convertDate($excelDate)
{
    // Convert the Excel serial date to a Y-m-d format
    return Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
}

private function convertTime($excelTime)
{
    // Convert the Excel time fraction to a H:i:s format
    $hours = floor($excelTime * 24);
    $minutes = floor(($excelTime * 24 * 60) % 60);
    $seconds = floor(($excelTime * 24 * 60 * 60) % 60);

    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}
}
