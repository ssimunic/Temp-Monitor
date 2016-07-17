<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateSensorRequest extends Request {

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
            'unique_id' => 'unique:sensors|min:3|alpha_num',
            'name' => 'required|min:3',
        ];
    }
}