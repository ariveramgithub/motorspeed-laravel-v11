<?php

namespace App\Http\Controllers\Calendario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Reminder;

use App\Mail\ReminderMail;
use Illuminate\Support\Facades\Mail;

class CalendarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('calendario/index', [
            'serverTime' => date('D M d Y H:i:s ', time()).'GMT'.date('O (e)', time()),
        ]);
    }

    public function getEvents(Request $request){
        $data = [];
        $events = Event::whereDate('event_start', '>=', $request->start)
            ->whereDate('event_start', '<=', $request->end)
            ->get();

        if( count($events) ){
            foreach($events as $k => $row){
                $data[] = (object)[
                    "title" => $row->event_name,
                    "start" => $row->event_start,
                    "end" => ($row->event_end) ? $row->event_end : null,
                    "backgroundColor" => ( date("Y-m-d") === date("Y-m-d", strtotime($row->event_start)) ) ? "#ed4b00" : "",
                    "borderColor" => ( date("Y-m-d") === date("Y-m-d", strtotime($row->event_start)) ) ? "#ed4b00" : "",
                    "url" => ($row->event_relationship && $row->event_id) ? url("/ordentrabajos")."/".$row->event_id."/edit" : "",
                    "allDay" => (bool)$row->all_day,
                    // extendedProps 
                    "pkId" => $row->id,
                    "description" => $row->event_description ? $row->event_description : "",
                    "inicio" => date("d-m-Y H:i", strtotime($row->event_start)),
                    "termino" => ($row->event_end) ? date("d-m-Y H:i", strtotime($row->event_end)) : "",
                ];
            }
        }

        return response()->json($data);
    }

    public function addEvent(Request $request){
        if($request->ajax()) {
            $event = Event::create([
                'event_name' => $request->event_name,
                'event_description' => $request->event_description,
                'event_start' => $request->event_start,
            ]);

            return response()->json($event);
        }
    }

    public function deleteEvent(Request $request){
        if($request->ajax() && $request->event_pk) {
            $event = Event::destroy($request->event_pk);
            return response()->json($event);
        }
    }

    public function getUpcomingEvents(Request $request){
        $data = [];
        $currentDatetime = $request->currentDatetime;

        $events = Event::where('event_start', '>=', date_format(date_create($currentDatetime),"Y-m-d H:i:s"))
            ->where('event_start', '<=', date_format(date_add(date_create($currentDatetime), date_interval_create_from_date_string("2 days")),"Y-m-d H:i:s"))
            ->get();

        if( count($events) ){
            foreach($events as $k => $row){
                $data[] = (object)[
                    "title" => $row->event_name,
                    "start" => $row->event_start,
                    "url" => ($row->event_relationship && $row->event_id) ? url("/ordentrabajos")."/".$row->event_id."/edit" : "",
                ];
            }
        }

        return response()->json($events);
    }


    public function reminders(){


        $reminders = Reminder::whereDate('start', date('Y-m-d', time()))
            ->get();

        foreach($reminders as $row){

        }

        Mail::to("ariveram@gmail.com")->send(new ReminderMail());

    }
}
