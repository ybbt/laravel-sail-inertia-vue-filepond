<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::orderBy('id'/*, 'DESC'*/)->get();

        return Inertia::render('FilesList', [ 'files' => $files, 'csrf_token' => csrf_token() ]);
    }

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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $tempFile = TempFile::where('folder', $request->new_file)->first();


        Storage::move('tmp/' . $tempFile->folder . '/' . $tempFile->filename, 'files/' . $tempFile->folder . '/' . $tempFile->filename);
        Storage::deleteDirectory('tmp/' . $tempFile->folder );

        Auth::user()->files()->create([
            'folder' => $tempFile->folder,
            'filename' => $tempFile->filename,
            'title' => $request->title,
        ]);

        $tempFile->delete();

        return redirect('/upload');
    }
}
