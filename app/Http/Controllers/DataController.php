<?php namespace App\Http\Controllers;

use App\Datalog;
use App\Sensor;
use App\SensorConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class DataController extends Controller {
    public $contact;
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Insert new data entry in database.
     * 
     * @return mixed
     */
    public function update()
    {

        if(!is_numeric(Input::get('t')) || !is_numeric(Input::get('h')))
        {
            if(Input::get('response_type')=="basic")
                return response("error")->header('Content-Type', 'text/plain');
            return Response::json(["status"=>"error", "reason"=>"Invalid temperature or humidity readings. Must be numeric value."]);
        }

        $sensor = Sensor::find(Input::get('id'));

        if(!$sensor)
        {
            if(Input::get('response_type')=="basic")
                return response("error")->header('Content-Type', 'text/plain');
            return Response::json(["status"=>"error", "reason"=>"Invalid sensor unique ID."]);
        }

        if($sensor->api_key!=Input::get('api_key'))
        {
            if(Input::get('response_type')=="basic")
                return response("error")->header('Content-Type', 'text/plain');
            return Response::json(["status"=>"error", "reason"=>"Invalid API key."]);
        }

        $sensor->last_temp = Input::get('t');
        $sensor->last_hum = Input::get('h');
        $sensor->last_check = date("F j, Y, g:i a");
//        $sensor->mac_address= Input::get('m');
//        $sensor->version = Input::get('f');

        $sensor->save();

        $datalog = new Datalog();
        $datalog->sensors_unique_id = Input::get('id');
        $datalog->temperature = Input::get('t');
        $datalog->humidity = Input::get('h');
        $datalog->date =  date('d.m.Y');
        $datalog->time =  date('H:i:s');
        $datalog->save();


        $this->contact = $sensor->contact;

        $sensorconfig = SensorConfig::where('sensors_unique_id', '=', Input::get('id'))->firstOrFail();

        if($sensor->contact_type == 1)
        {
            if(Input::get('t')> $sensorconfig->max_temp || Input::get('t')< $sensorconfig->min_temp )
            {
                Mail::raw('Sensor '.$sensor->name.' temperature is abnormal: '.Input::get('t').' °C', function($message)
                {
                    $message->subject('Temperature alert!');
                    $message->from('admin@tempmonitor.com.hr', env('WEBNAME'));
                    $message->to($this->contact);
                });
            }

            if(Input::get('h')> $sensorconfig->max_hum || Input::get('h')< $sensorconfig->min_hum )
            {
                Mail::raw('Sensor '.$sensor->name.' humidity is abnormal: '.Input::get('h').' %', function($message)
                {
                    $message->subject('Humidity alert!');
                    $message->from('admin@tempmonitor.com.hr', env('WEBNAME'));
                    $message->to($this->contact);
                });
            }

        }


        if(Input::get('response_type')=="esp8266")
        {
            $r = "";
            if($sensor->save() && $datalog->save())
                $r.= "QS1=SUCCESSQE1\n";
            else
                $r.="QS2=ERRORQE2\n";

            $sensorconfig = SensorConfig::where('sensors_unique_id', '=', Input::get('id'))->firstOrFail();
            $r.="QS2=".$sensorconfig->deepsleep_time."QE2\n";
            $r.="QS3=".$_ENV['SERVER']."QE3";
            return response($r)
                ->header('Content-Type', 'text/plain');
        }
        else if(Input::get('response_type')=="basic"){
            $r = null;

            if($sensor->save() && $datalog->save())
                $r="success";
            else
                $r="error";

            return response($r)
                ->header('Content-Type', 'text/plain');
        }
        else {
            $status = null;
            $reason = null;

            if($sensor->save() && $datalog->save())
            {
                $status = "success";
            }
            else
            {
                $status = "error";
                $reason = "Error writing into database.";
            }

            $sensorconfig = SensorConfig::where('sensors_unique_id', '=', Input::get('id'))->firstOrFail();

            $response = [];

            $response["status"] = $status;
            if($reason)
                $response["reason"] = $reason;
            $response["read_every"] = $sensorconfig->deepsleep_time;

            return Response::json($response);
        }
    }


    /**
     * Get JSON data used to display charts.
     *
     * @param $unique_id
     * @param $type
     * @return mixed
     */
    public function data($unique_id, $type)
    {
        if($type=="live")
        {
            $sensor = Sensor::where('unique_id', '=', $unique_id)->firstOrFail();
            $temperature = round($sensor->last_temp, 2);
            $humidity = round($sensor ->last_hum, 2);

            $time = time() * 1000;

            return Response::json(array(array($time, $temperature), array($time, $humidity)));
        }

        if($type=="24hours")
        {
            $data = Datalog::where('sensors_unique_id', '=', $unique_id)->get();

            $jdata = json_decode($data);

            $new = array();

            $start = strtotime("-1 day");

            foreach($jdata as $entry)
            {
                $datetime = strtotime($entry->date." ".$entry->time);


                if($datetime > $start)
                {
                    // 3600 - a hour
                    $d = date("Y-m-d H:00:00", strtotime($entry->date." ".$entry->time));
                    array_push($new, array(strtotime($d), round($entry->temperature, 2)));
                }
            }


            $latest = $new[0][0];

            $all = array();
            $max = null;
            $min = null;
            $avg = null;

            $ranges = array();
            $averages = array();

            foreach($new as $entry)
            {
                if($latest!=$entry[0])
                {
                    $avg = array_sum($all)/count($all);
                    array_push($ranges, array($latest*1000, $min, $max));
                    array_push($averages, array($latest*1000, round($avg,1)));
                    $latest = $entry[0];

                    $all = array();

                    $min = null;
                    $max = null;
                    $avg = 0;

                }
                else
                {
                    if($max==null) $max = $entry[1];
                    if($min==null) $min= $entry[1];

                    if($entry[1]>$max) $max = $entry[1];
                    if($entry[1]<$min) $min = $entry[1];
                    array_push($all, $entry[1]);
                }
            }

            return Response::json(array($ranges, $averages));
        }

        if($type=="30days")
        {
            $data = Datalog::where('sensors_unique_id', '=', $unique_id)->get();

            $jdata = json_decode($data);

            $new = array();

            $start = strtotime("-30 days");

            foreach($jdata as $entry)
            {
                $datetime = strtotime($entry->date." ".$entry->time);

                if($datetime > $start)
                {
                    array_push($new, array(strtotime($entry->date), round($entry->temperature, 2)));
                }
            }

            $latest = $new[0][0];

            $all = array();
            $max = null;
            $min = null;
            $avg = null;

            $ranges = array();
            $averages = array();

            foreach($new as $entry)
            {
                if($latest!=$entry[0])
                {
                    $avg = array_sum($all)/count($all);
                    array_push($ranges, array($latest*1000, $min, $max));
                    array_push($averages, array($latest*1000, round($avg,1)));
                    $latest = $entry[0];

                    $all = array();

                    $min = null;
                    $max = null;
                    $avg = 0;
                }
                else
                {
                    if($max==null) $max = $entry[1];
                    if($min==null) $min= $entry[1];

                    if($entry[1]>$max) $max = $entry[1];
                    if($entry[1]<$min) $min = $entry[1];
                    array_push($all, $entry[1]);
                }
            }

            return Response::json(array($ranges, $averages));
        }
    }

}
