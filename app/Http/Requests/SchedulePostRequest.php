<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 03.10.18
 * Time: 8:15
 */

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SchedulePostRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|max:500',
            'date' => 'required|after:yesterday',
            'time' => (function (){
                $time = 'required';
                if ($this->request->all()['date'] == date('Y-m-d')) $time .= '|after:now';
                return $time;
            })(),
            'attachments.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}