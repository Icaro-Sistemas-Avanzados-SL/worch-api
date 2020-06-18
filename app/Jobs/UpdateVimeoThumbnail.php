<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Vimeo\Laravel\Facades\Vimeo;

class UpdateVimeoThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file_id;
    protected $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_id, $video)
    {
        $this->file_id = $file_id;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video = Vimeo::request($this->video);
        if($video['body']['upload']['status'] != "complete") {
            Log::debug('Error status upload, retry');
            $delay = 60 * 5;
            $this->release($delay);
        } else {
            Log::debug('Updating file');
            $file = File::find($this->file_id);
            $thumbnail_horizontal = $video['body']['pictures']['sizes'][3]['link_with_play_button'];
            $thumbnail_vertical = $video['body']['pictures']['sizes'][7]['link_with_play_button'];
            $file->thumbnail_horizontal = $thumbnail_horizontal;
            $file->thumbnail_vertical = $thumbnail_vertical;
            $file->save();
        }
    }
}
