<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\TempFile;
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
        $files = File::orderBy('id'/*, 'DESC'*/)->get();

        return Inertia::render('FilesList', ['files' => FileResource::collection($files), 'csrf_token' => csrf_token(), ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request)
    {
        $validated = $request->validated();

        $tempFile = TempFile::where('folder', $validated['new_file'])->first();

        $idPath = $tempFile->folder . '/' . $tempFile->filename;
        $fromPath = 'tmp/' . $idPath;
        $toPath = 'files/' . $idPath;

        Storage::move($fromPath, $toPath);
        Storage::deleteDirectory('tmp/' . $tempFile->folder);

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        $validated = $request->validated();

        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/custom.log'),
        ])->info($validated);

        if ($file->folder === $validated["new_file"]) {
            $file->update([
                'title' => $validated["title"],
            ]);
        } else {
            $tempFile = TempFile::where('folder', $validated["new_file"])->first();


            $idPath = $tempFile->folder . '/' . $tempFile->filename;
            $fromPath = 'tmp/' . $idPath;
            $toPath = 'files/' . $idPath;

            Storage::move($fromPath, $toPath);
            Storage::deleteDirectory('tmp/' . $tempFile->folder);
            Storage::deleteDirectory('files/' . $file->folder);

            $file->update([
                'folder' => $tempFile->folder,
                'filename' => $tempFile->filename,
                'title' => $validated["title"],
            ]);

            $tempFile->delete();
        }


        return redirect('/upload');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        Storage::deleteDirectory('files/' . $file->folder);
        $file->delete();
        return redirect('/upload');
    }

    public function load($serverId)
    {
        $user = Auth::user();

        $file = File::where('folder', $serverId)->first();

        if ($user->can('load', $file)) {
            return Storage::download("files/" . $file->folder . "/" . $file->filename);
        }
        return "";




    }
}
