<?php namespace App\Http\Controllers;

use App\Sensor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        if(!Auth::user()) return Redirect::to('/');
        $sensors = Sensor::where('users_id', '=', Auth::user()->id)->get();
        $sensors_count = $sensors->count();
		return view('home')->with(array('sensors' => $sensors, 'sensors_count' => $sensors_count));
	}

	/**
	 * Show get started page.
	 *
	 * @return Response
	 */
	public function getstarted()
	{
		return view('getstarted');
	}
}
