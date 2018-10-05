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
    public function rules()
    {
        return [
            'text' => 'required|max:500',
            'date_to_post' => 'required|unique:schedule_posts,date_to_post|after:now',
            'attachments.*' => 'active_url',
        ];
    }
}