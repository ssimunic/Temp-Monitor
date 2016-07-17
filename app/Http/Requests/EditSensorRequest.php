<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditSensorRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name' => 'required|min:3',
            'deepsleep_time' => 'required|numeric',
            'min_temp' => 'required|numeric',
            'max_temp' => 'required|numeric',
            'min_hum' => 'required|numeric',
            'max_hum' => 'required|numeric',
        ];
    }
}