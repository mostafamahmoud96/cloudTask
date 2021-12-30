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
        if ($request->file) {
            $fileModel = new File();
            $uploaded_file = $request->file;
            $path = public_path("uploads");
            $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $request->file->getClientOriginalName()); //remove extenison
            $fileName = md5($withoutExt) . '.' . $uploaded_file->getClientOriginalExtension();
            if (File::where('name', md5($withoutExt))->exists()) {
                $files = scandir($path);
                $files = array_diff(scandir($path), array('.', '..'));
                $matchingFiles = preg_grep('{' . $fileName . '}', $files);
                if ($matchingFiles) {
                    foreach ($matchingFiles as $file) {
                        return response()->json(['status' => 200, 'message' => 'you already uploaded the file before', 'file' =>
                        asset("/uploads/$file")]);
                    }
                }
            } elseif (!File::where('name', md5($withoutExt))->exists()) {
                $fileModel->name = md5($withoutExt);
                $uploaded_file->move($path, $fileName);
                $fileModel->file_path = '/uploads/' . $fileName;
                $fileModel->save();

                return response()->json(['status' => 200, 'message' => 'File uploaded successfully']);
            } else {
                return response()
                    ->json(['status' => 404, 'message' => 'You Must upload type file']);
            }
        } else {
            return response()
                ->json(['status' => 404, 'message' => 'some thing went wrong']);
        }
    }


    public function get_file_by_name(Request $request)
    {
        if ($request->has('name')) {
            $fileName = md5($request->name);
            $file = File::Where('name', $fileName)->first() ?? null;
            if ($file) {
                return response()->json(['status' => 200, 'file_name' => $request->name, 'file' => asset($file->file_path)]);
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
            $file = File::Where('name', $fileName)->first();
            if ($file) {
                if (file_exists(public_path($file->file_path))) {
                    unlink(public_path($file->file_path));
                };
                $file->delete();
                return response()->json(['status' => 200, 'message' => 'File is Deleted Successfully']);
            } else {
                return response()
                    ->json(['status' => 404, 'message' => 'file does not exist']);
            }
        }
        else
        {
            return response()
                ->json(['status' => 404, 'message' => 'input file name can not be empty']);
        }
    }
}
