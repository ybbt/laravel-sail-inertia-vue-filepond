<?php

namespace App\Jobs;

use App\Models\TempFile;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessDeleteTempFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dateNow = Carbon::now();
        $tempFiles = TempFile::where("created_at", "<", $dateNow->subMinutes(1));
        $tempFilesInst = $tempFiles->get();

        if (isset($tempFilesInst)){
            foreach ($tempFilesInst as $file){
                Storage::deleteDirectory("tmp/" . $file->folder);
            }
            $tempFiles->delete();
        }

    }
}
