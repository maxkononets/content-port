<?php

namespace App\Console\Commands;

use App\Jobs\PublishPost;
use App\SchedulePost;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckReadinessPost extends Command
{
    public $posts;
    public $time;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   protected $signature = 'checkreadinesspost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check readiness posts to publish and add ready of them to queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->time = new Carbon();
        $this->posts = SchedulePost::where('publication_time', '<', $this->time->toDateTimeString())->get();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->posts as $post) {
            PublishPost::dispatch($post)->delay(Carbon::now());
        }
  }
}