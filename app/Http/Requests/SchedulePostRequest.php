<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 03.10.18
 * Time: 8:15
 */

namespace App\Http\Requests;

use Carbon\Carbon;
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
        $userTimezone = $this->request->all()['timezone'];
        date_default_timezone_set($userTimezone);
        $userTime = new Carbon('', $userTimezone);
        $postTime = new Carbon($this->request->all()['date'] . ' ' . $this->request->all()['time'], $userTimezone);

        return [
            'text' => 'required|max:500',
            'date' => 'required_with:time|after:yesterday|nullable',
            'time' => $this->validDate($userTime, $postTime),
        ];
    }

    public function validDate($userTime, $postTime)
    {
        $time = 'required_with:date|nullable';
        if ($userTime->toDateString() == $postTime->toDateString()) {
            $time .= '|after:now';
        }
        return $time;
    }
}