<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload_file(Request $request)
    {

        $fileModel = new File();
        if($request->file()) {
            $fileName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $fileModel->name = time().'_'.$request->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();
            $message = array( 'message' => 'file Uploaded Successfully', 'status' => 200);
            return $message;
        }
        else
        {
            $message = array('message' => 'file Not found', 'status' => 404);
            return $message;
        }

    }


    public function get_file_by_name(Request $request)
    {
        $allFiles = Storage::files('public/uploads');
        $matchingFiles = preg_grep('{' . $request->name . '}', $allFiles);
        foreach ($matchingFiles as $path) {
            return Storage::get($path);
        }

    }

    public function delete_file_by_name(Request $request)
    {
        $allFiles = Storage::files('public/uploads');
        $matchingFiles = preg_grep('{' . $request->name . '}', $allFiles);
        foreach ($matchingFiles as $path) {
             Storage::delete($path);

        }

    }
}