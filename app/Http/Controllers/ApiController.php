<?php namespace App\Http\Controllers;

use App\Sensor;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller {

    /**
     * Return JSON data of sensor with it's config.
     *
     * @param $unique_id
     * @return mixed
     */
    public function sensorData($unique_id)
    {
        $sensor = Sensor::where('unique_id', '=', $unique_id)->with('sensorconfig')->get();

        return Response::json(array($sensor));

    }

}
