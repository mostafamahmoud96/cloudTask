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
        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $fileModel->name = time() . '_' . $request->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();
            return response()
            ->json(['status' => 200, 'message' => 'File uploaded successfully']);
        } else {
            return response()
            ->json(['status' => 404, 'message' => 'Something went Wrong']);

        }
    }


    public function get_file_by_name(Request $request)
    {
        $path = public_path("storage/uploads");
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        $matchingFiles = preg_grep('{' . $request->name . '}', $files);
        if ($matchingFiles) {
            foreach ($matchingFiles as $file) {
            
                return  response()->json(['status' => 200, 'file' => asset("storage/uploads/$file")]);
            }
        } else {
            return response()
                ->json(['status' => 404, 'message' => 'No file matching found']);
        }
    }

    public function delete_file_by_name(Request $request)
    {

        $allFiles = Storage::files('public/uploads');
        $matchingFiles = preg_grep('{' . $request->name . '}', $allFiles);
        if(count($matchingFiles))
        {
            foreach ($matchingFiles as $path) {
                 $delete = Storage::delete($path);
                }
                if($delete)
                {
                    return  response()->json(['status' => 200, 'message' => "file deleted successfull"]);

                }
        }
        else
        {
            return  response()->json(['status' => 404, 'message' => "No file matching found"]);
        }
    }
}
