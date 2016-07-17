<?php namespace App\Http\Controllers;

use App\Datalog;
use App\Http\Requests\CreateSensorRequest;
use App\Http\Requests\EditSensorRequest;
use App\Sensor;
use App\SensorConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class SensorController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show sensors of logged user.
     *
     * @return $this
     */
    public function index()
    {
        $sensors = Sensor::where('users_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('sensors.index')->with(array(
            'sensors' => $sensors,
        ));
    }


    /**
     * Create new sensor and show updated list of sensors.
     *
     * @param CreateSensorRequest $request
     * @return \Illuminate\View\View
     */
    public function create(CreateSensorRequest $request)
    {
        if(Input::get('name') == "") return Redirect::route('sensor')->with('message', 'There was problem adding a sensor. Please try again.');

        $sensor = new Sensor();
        $unique_id=null;
        if(Input::get('unique_id')=="")
        {
            $unique_id = strtoupper(substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 10)), 0, 10));
        }
        else
        {
            $unique_id =  Input::get('unique_id');
        }
        $sensor->unique_id = $unique_id;
        $sensor->name = Input::get('name');;
        $sensor->users_id = Auth::user()->id;
        $sensor->api_key = md5(microtime().rand());

        if($sensor->save()) {
            $sensorconfig = new SensorConfig();
            $sensorconfig->sensors_unique_id = $unique_id;
            $sensorconfig->save();

            return Redirect::route('sensor')->with('message', 'Sensor has been added.');
        } else {
            return Redirect::route('sensor')->with('message', 'There was problem adding a sensor. Please try again.');
        }

        return view('sensors.index');
    }

    /**
     * Show API guide of sensor to authorized user.
     *
     * @param $unique_id
     * @return $this|Redirect
     */
    public function apiShow($unique_id)
    {
        $sensor = Sensor::where('unique_id', '=', $unique_id)->with('sensorconfig')->firstOrFail();

        if($sensor->users_id != Auth::user()->id) {
            return redirect('sensor');
        }


        return view('sensors.api')->with(array('sensor' => $sensor));
    }

    /**
     * Show specific sensor of authorized user.
     *
     * @param $unique_id
     * @return $this|Redirect
     */
    public function show($unique_id)
    {
        $sensor = Sensor::where('unique_id', '=', $unique_id)->with('sensorconfig')->firstOrFail();

        if($sensor->users_id != Auth::user()->id) {
            return redirect('sensor');
        }


        return view('sensors.show')->with(array('sensor' => $sensor));
    }

    /**
     * Delete specific sensor of authorized user.
     *
     * @param $unique_id
     * @return Redirect
     */
    public function delete($unique_id)
    {
        $sensor = Sensor::find($unique_id);

        if($sensor->users_id != Auth::user()->id) {
            return redirect('sensor');
        }

        $sensor->destroy($unique_id);
        return redirect('sensor');
    }

    /**
     * Edit specific sensor of authorized user.
     *
     * @param EditSensorRequest $request
     * @return Redirect
     */
    public function edit(EditSensorRequest $request)
    {
        $sensor = Sensor::where('unique_id', '=', Input::get('unique_id'))->with('sensorconfig')->firstOrFail();

        if($sensor->users_id != Auth::user()->id) {
            return redirect('sensor');
        }

        var_dump(Input::all());

        $sensor->name = Input::get('name');
        $sensor->notes = Input::get('notes');

        $sensorconfig = SensorConfig::where('sensors_unique_id', '=', Input::get('unique_id'))->firstOrFail();

        $sensorconfig->deepsleep_time = Input::get('deepsleep_time');
        $sensorconfig->min_temp = Input::get('min_temp');
        $sensorconfig->max_temp = Input::get('max_temp');
        $sensorconfig->min_hum = Input::get('min_hum');
        $sensorconfig->max_hum = Input::get('max_hum');

        $sensorconfig->save();

        $sensor->contact = Input::get('contact');


        $sensor->contact_type = Input::get('contact_type');
        if(Input::get('alertme')==false) $sensor->contact_type = 0;
        $sensor->location = trim(Input::get('location'), '()');

        $sensor->save();

        return redirect('sensor/'.Input::get('unique_id'))->with('message', 'Settings have been saved.');
    }

    /**
     * Show monitor chars for specific sensor.
     *
     * @param $unique_id
     * @return $this
     */
    public function monitor($unique_id)
    {
        $sensor = Sensor::where('unique_id', '=', $unique_id)->firstOrFail();

        if($sensor->users_id != Auth::user()->id) {
            return redirect('sensor');
        }

        return view('sensors.monitor')->with(array('sensor' => $sensor));
    }
}
