<?php

namespace App\Mail;

use App\Jobs\PublishPost;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\SchedulePost;


class PostSend extends Mailable
{
    use Queueable, SerializesModels;
    protected $group_id;
    protected $facebookId;
    protected $user;
    protected $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$group_id,$text)
    {
        $this->group_id = $group_id;
        //$this->facebookId = Group::find($schedulePost->group_id)->facebookAccounts->first()->id;
        $this->user = $user;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email')->
        with([
            'Name' => $this->user,
            'Group_id' => $this->group_id,
            'text' => $this->text
        ]);
    }
}
