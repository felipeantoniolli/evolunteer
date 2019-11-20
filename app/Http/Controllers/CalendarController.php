<?php

namespace App\Http\Controllers;

use App\Model\Institution;
use App\Model\Solicitation;
use App\Model\Work;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function getCalendarByVolunteerId(Request $request)
    {
        $req = $request->all();

        $solicitations = Solicitation::where([
            ['id_volunteer', $req['id_volunteer']],
            ['approved', 1]
        ])->get();

        if (!$solicitations) {
            return GeneralController::jsonReturn(true, 200, null, 'No calendar to display.');
        }

        $institutionsId = [];
        foreach ($solicitations as $one) {
            $institutionsId[$one->id_institution] = $one->id_institution;
        }

        $institutions = Institution::whereIn('id_institution', $institutionsId)->get();

        $date = date('Y-m-d h:i:s');
        $works = Work::whereIn('id_institution', $institutionsId)
            ->where('work_date', '>', $date)
            ->orderBy('work_date')
            ->get();

        if (!$works) {
            return GeneralController::jsonReturn(true, 200, null, 'No calendar to display.');
        }

        $instutionsData = [];
        foreach ($institutions as $one) {
            $instutionsData[$one->id_institution] = $one;
        }

        $json = [];
        foreach ($works as $one) {
            $work = [
                'work' => $one
            ];

            $work['work']['institution'] = $instutionsData[$one->id_institution];

            $json[] = $work;
        }

        return GeneralController::jsonReturn(true, 200, $json, 'Calendars successfully found.');
    }
}
