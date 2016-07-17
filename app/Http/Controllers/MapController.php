<?php namespace App\Http\Controllers;

use App\Datalog;
use App\Http\Requests\CreateMapRequest;
use App\Map;
use App\MapEntry;
use App\Sensor;
use App\SensorConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MapController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show basic Google map.
     *
     * @return $this
     */
    public function mapBasic()
    {
        $sensors = Sensor::where('users_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'asc')
            ->with('sensorconfig')
            ->get();

        return view('maps.basic')->with(array(
            'sensors' => $sensors,
        ));
    }

    /**
     * Show customs maps for authorized user.
     *
     * @return $this
     */
    public function mapCustom()
    {
        $sensors = Sensor::where('users_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'asc')
            ->with('sensorconfig')
            ->get();

        $maps = Map::where('users_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'asc')
            ->with('mapentry')
            ->get();

        return view('maps.custom')->with(array(
            'sensors' => $sensors,
            'maps' => $maps,
        ));
    }

    /**
     * Create new custom map.
     *
     * @return \Illuminate\View\View
     */
    public function store(CreateMapRequest $request)
    {
        if(Input::get('name') == "" || Input::get('maptype') == "") return Redirect::route('map')->with('message', 'There was problem creating a map. Please try again.');

        $map = new Map();
        $map->name = Input::get('name');
        $map->users_id = Auth::user()->id;
        $map->type = Input::get('maptype');

        if(Input::get('maptype')==2)
        {
            $file = array('image' => Input::file('image'));
            $rules = array('image' => 'required',);
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                Session::flash('error', 'Uploaded area photo is not valid. Image size may be too big.');
                return Redirect::to('map/custom');
            } else {
                if (Input::file('image')->isValid()) {
                    $destinationPath = 'uploads';
                    $extension = Input::file('image')->getClientOriginalExtension();
                    $fileName = rand(11111, 99999) . '.' . $extension; //
                    Input::file('image')->move($destinationPath, $fileName);
                    $map->image_path = $fileName;
                } else {
                    Session::flash('error', 'Uploaded area photo is not valid. Image size may be too big.');
                    return Redirect::to('map/custom');
                }
            }

        }

        if($map->save()) {
            return Redirect::route('map')->with('message', 'Map has been created.');
        } else {
            return Redirect::route('map')->with('message', 'There was problem creating a map. Please try again.');
        }

        return view('map.index');
    }

    /**
     * Show custom map.
     *
     * @param $id
     * @return $this|Redirect
     */
    public function show($id)
    {
        $map = Map::where('id', '=', $id)->firstOrFail();

        if($map->users_id != Auth::user()->id) {
            return redirect('map');
        }

        $sensors = Sensor::where('users_id', '=', Auth::user()->id)->get();

        $mapentries = MapEntry::where('maps_id', '=', $map->id)->with('sensor')->get();

        if($map->type==1) {

        }
        if($map->type==2)
        {
            return view('maps.area')->with(array('map' => $map, 'mapentries' => $mapentries, 'sensors' => $sensors));
        }
    }

    /**
     * Add sensor to custom map.
     *
     * @param $id
     * @param $unique_id
     * @return Redirect
     */
    public function addSensorToMap($id, $unique_id)
    {
        $map = Map::where('id', '=', $id)->firstOrFail();

        if($map->users_id != Auth::user()->id) {
            return redirect('map');
        }

        $dentry = MapEntry::where('sensors_unique_id', '=', $unique_id)->where('maps_id', '=', $id)->get();

        if(!empty($dentry[0]))
            return Redirect::to('/map/view/'.$id)->with('message', 'This sensor already exists on this map.');

        $mapentry = new MapEntry();
        $mapentry->maps_id = $id;
        $mapentry->sensors_unique_id = $unique_id;
        $mapentry->map_location = "50px:50px";

        if($mapentry->save()) {
            return Redirect::to('/map/view/'.$id)->with('message', 'Sensor has been added.');
        } else {
            return Redirect::to('/map/view/'.$id)->with('message', 'There was problem adding a sensor. Please try again.');
        }

    }

    /**
     * Edit or delete (at top left) map entry from custom map.
     *
     * @param $id
     * @param $location
     * @return Redirect|string
     */
    public function editMapEntry($id, $location)
    {
        $mapentry = MapEntry::find($id);

        $map = Map::find($mapentry->maps_id);

        if($map->users_id != Auth::user()->id)
        {
            return redirect('sensor');
        }

        if($location == "0px:0px" || $location == "0pct:0pct") {
            $mapentry->destroy($mapentry->id);
            return 'deleted';

        } else {
            $mapentry->map_location = $location;
            $mapentry->save();
            return 'moved';
        }



    }

    /**
     * Delete custom map.
     *
     * @param $id
     * @return Redirect
     */
    public function delete($id)
    {
        $map = Map::find($id);

        if($map->users_id!=Auth::user()->id) {
            return redirect('map');

        }

        $map->delete($map->id);

        return Redirect::route('map')->with('message', 'Map has been deleted.');

    }
}
