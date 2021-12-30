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
        if ($request->file()) {
            $fileModel = new File();
            $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $request->file->getClientOriginalName()); //remove extenison
            $fileName = md5($withoutExt);
            if (File::where('name', $fileName)->exists()) {
                $path = (storage_path('app/uploads'));
                $files = scandir($path);
                $files = array_diff(scandir($path), array('.', '..'));
                $matchingFiles = preg_grep('{' . $fileName . '}', $files);
                if ($matchingFiles) {
                    foreach ($matchingFiles as $file) {
                        return  response()->json(['status' => 200, 'message' => 'you already uploaded the file before', 'file' => asset("uploads/$file")]);
                    }
                }
            } elseif (!File::where('name', $fileName)->exists()) {
                $filePath = $request->file('file')->storeAs("uploads", $fileName);
                $fileModel->name = $fileName;
                $fileModel->file_path =  $filePath;
                $fileModel->save();
                return response()->json(['status' => 200, 'message' => 'File uploaded successfully']);
            } else {
                return response()
                    ->json(['status' => 404, 'message' => 'You Must upload type file']);
            }
        } else {

            return response()
                ->json(['status' => 404, 'message' => 'You Must upload type file']);
        }
    
    }



    public function get_file_by_name(Request $request)
    {
        if ($request->has('name')) {
            $fileName = md5($request->name);
            $path = ("storage/uploads");
            $file = File::Where('name', $fileName)->first()->name ?? null;
            if ($file) {
                return  response()->json(['status' => 200, 'File Name' => $request->name, 'file' => asset("storage/uploads/$file")]);
            } else {
                return response()
                    ->json(['status' => 404, 'message' => 'No Matching File']);
            }
        } else {
            return response()
                ->json(['status' => 404, 'message' => 'Input file Name Can not be empty']);
        }
    }

    public function delete_file_by_name(Request $request)
    {


        if ($request->has('name')) {
            $fileName = md5($request->name);
            $allFiles = Storage::allFiles('uploads');
            $matchingFile = preg_grep('{' . md5($request->name) . '}', $allFiles);
            Storage::delete($matchingFile); // remove from uploads file
            $file = File::Where('name', $fileName)->first() ?? null;
            if ($file) {
                $file->delete();
                return  response()->json(['status' => 200, 'message' => 'File is Deleted Successfully']);
            } else {
                return response()
                    ->json(['status' => 404, 'message' => 'No Matching File to be deleted']);
            }
        } else {

            return response()
                ->json(['status' => 404, 'message' => 'Input file Name Can not be empty']);
        }
    }
}
