<?php namespace App\Http\Controllers;

use App\Http\Requests\SaveProfileRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user profile settings.
     * 
     * @return $this
     */
    public function profile()
    {
        $user = Auth::user();

        return view('user.profile')->with(array(
            'user' => $user,
        ));
    }

    /**
     * Save user profile on submit.
     *
     * @return mixed
     */
    public function profileSave(SaveProfileRequest $request)
    {
        $user = User::find(Auth::user()->id);

        $email = Input::get('email');
        $password = Input::get('password');

        if($password) {
            $user->password = Hash::make($password);
            Auth::logout();

        }

        $user->email = $email;

        $user->save();
        return Redirect::to('profile');

    }

}
