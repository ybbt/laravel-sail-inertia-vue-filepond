<?php

namespace App\Http\Controllers;

use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TempFileController extends Controller
{
    public function upload(Request $request){

        if ($request->hasFile("new_file")){
            $inputFile = $request->new_file;//file("new_file");
            $fileName = $inputFile->getClientOriginalName();
            $folderName = uniqid() . "-" . now()->timestamp;
            Storage::disk('local')->put('tmp/' . $folderName . '/' . $fileName, 'Contents');

            TempFile::create([
                'folder' => $folderName,
                'filename' => $fileName
            ]);

            return $folderName;
        }
        return '';
    }

    public function delete(Request $request){
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/custom.log'),
        ])->info($request->getContent());
        $tempFile = TempFile::where('folder', $request->getContent())->first();
        Storage::deleteDirectory('tmp/' . $request->getContent());
        $tempFile->delete();
    }

}
