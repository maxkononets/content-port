<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schedulePosts()
    {
        return $this->belongsToMany('App\SchedulePost');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo();
    }

    /**
     * @param Model $owner
     * @return array
     */
    public static function getOwnerAttachments(Model $owner)
    {

        $images = $owner->attachments()->where('entity_type', Image::class)->paginate(16);
        $videos = $owner->attachments()->where('entity_type', Video::class)->paginate(16);

        $attach = [
            'images' => $images->map(function ($item){
                return $item->entity;
            }),
            'videos' => $videos->map(function ($item){
                return $item->entity;
            }),
            'image_next' => $images->nextPageUrl(),
            'video_next' => $videos->nextPageUrl(),
            'image_last_page' => $images->lastPage(),
            'video_last_page' => $videos->lastPage(),
        ];
        return $attach;
    }

    public static function saveOnIds($post, $ids)
    {
        if(isset($ids['videos'])){
            $identifier = $ids['videos'];
            $class = Video::class;
        }
        if(isset($ids['images'])){
            $identifier = $ids['images'];
            $class = Image::class;
        }
        if (isset($class) && isset($identifier)) {
            $attachmentEntity = $class::find($identifier);
            $post->attachments()->saveMany($attachmentEntity);
        } else {
            return;
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function store($request)
    {
        $attachments = new Collection();

        if ($request->hasFile('images')) {
            foreach ((array) $request->file('images') as $image) {
                $objAttachment = new self();
                $objImage = Image::store($image);
                Auth::user()->attachments()->save($objAttachment);
                $objImage->attachments()->save($objAttachment);
                $attachments->push([
                    'id' => $objImage->id,
                    'route' => $objImage->route,
                    'type' => 'image',
                ]);
            }
        }

        if ($request->hasFile('videos')) {
            foreach ((array) $request->file('videos') as $video) {
                $objAttachment = new self();
                $objVideo = Video::store($video);
                Auth::user()->attachments()->save($objAttachment);
                $objVideo->attachments()->save($objAttachment);
                $attachments->push([
                    'id' => $objVideo->id,
                    'route' => $objVideo->route,
                    'type' => 'video',
                ]);
            }
        }
        return response($attachments->toJson());
    }

    /**
     * @param $data
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public static function storeFromLinks($data)
    {
        $guzzle = new Client();
        $response = [];
        if (isset($data['videos'])){
            $response['videos'] = array_map(function ($item) use($guzzle){
                $file = $guzzle->get($item)->getBody()->getContents();
                Storage::putFile('videoss', $file);
                $objAttachment = new self();
                $objVideo = Video::store($file);
//                dump($objVideo);
                Auth::user()->attachments()->save($objAttachment);
                $objVideo->attachments()->save($objAttachment);
                return [
                    'id' => $objVideo->id,
                    'route' => $objVideo->route,
                    'type' => 'video',
                ];

             }, $data['videos']);
            return $response;
        }

        if (isset($data['images'])){
            $response['images'] = array_map(function ($item) use($guzzle){
                $file = $guzzle->get($item);
                return $file->getBody()->getContents();
            }, $data['images']);
        }
//       $response = self::store($response);
//        return $response;
    }
}