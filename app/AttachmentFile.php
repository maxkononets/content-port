<?php
/**
 * Created by PhpStorm.
 * User: maxym
 * Date: 19.10.18
 * Time: 18:42
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

abstract class AttachmentFile extends Model
{

    public static function store($attachment)
    {
        $className = strtolower(substr(strrchr(get_called_class(), "\/"), 1));
        $name = $attachment->getClientOriginalName();
        $size = $attachment->getSize();
        $route = '/storage/' . $attachment->store('attachments/' . $className);
        $instance = new static();
        $instance->fill([
            'name' => $name,
            'size' => $size,
            'route' => $route,
        ])->save();
        return $instance;
    }
}