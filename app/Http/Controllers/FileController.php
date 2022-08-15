<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Models\File;
use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $url = Storage::url('62f6ee8cf286e-1660350092/avatar.jpg');
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/custom.log'),
        ])->info($url);

        $files = File::orderBy('id'/*, 'DESC'*/)->get();

        return Inertia::render('FilesList', [ 'files' => $files, 'csrf_token' => csrf_token() ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request)
    {
        $validated = $request->validated();

        $tempFile = TempFile::where('folder', $validated['new_file'])->first();


        Storage::move('tmp/' . $tempFile->folder . '/' . $tempFile->filename, 'files/' . $tempFile->folder . '/' . $tempFile->filename);
        Storage::deleteDirectory('tmp/' . $tempFile->folder );

        Auth::user()->files()->create([
            'folder' => $tempFile->folder,
            'filename' => $tempFile->filename,
            'title' => $validated['title'],
        ]);

        $tempFile->delete();

        return redirect('/upload');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {

        $tempFile = TempFile::where('folder', $request->new_file)->first();
        Storage::move('tmp/' . $tempFile->folder . '/' . $tempFile->filename, 'files/' . $tempFile->folder . '/' . $tempFile->filename);
        Storage::deleteDirectory('tmp/' . $tempFile->folder );
        Storage::deleteDirectory('files/' . $file->folder );

        $file->update([
            'folder' => $tempFile->folder,
            'filename' => $tempFile->filename,
            'title' => $request->title,
        ]);

        $tempFile->delete();

        return redirect('/upload');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        Storage::deleteDirectory('files/' . $file->folder );
        $file->delete();
        return redirect('/upload');
    }
}
