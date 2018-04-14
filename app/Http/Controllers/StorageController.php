<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    /**
     * /file/{fileType}/{id}/{ext}/{thumbnail?}
     */
    public function getFile($type, $id, $ext, $thumbnail = false) // TODO auth check. some public, some private

    {
        switch ($type) {
           
            case 'profile_photo':
                if ($thumbnail == 1) {
                    $target = PROFILE_IMAGE_PATH . "/thumbs/{$id}.{$ext}"; 
                } else {
                    $target = PROFILE_IMAGE_PATH . "/{$id}.{$ext}";
                } 
                break;
                
            default:
                abort(500);
        }

       $target = str_replace('public/', '', $target) ;

        if (Storage::disk('public')->exists($target)) {

            $file = Storage::disk('public')->get($target);
            $mimeType = Storage::disk('public')->getMimeType($target);

            return response($file)->header('Content-Type', $mimeType);

        } elseif ($thumbnail) {
            switch ($ext) {
                case 'doc':
                case 'docx':
                    $file = 'images/file_doc.gif';
                    break;

                case 'pdf':
                    $file = 'images/file_pdf.gif';
                    break;

                case 'xls':
                case 'xlsx':
                    $file = 'images/file_xls.gif';
                    break;

                default:
                    $file = 'images/file.gif';

            }
            $file = Storage::disk('public')->get($file);
            return response($file)->header('Content-Type', 'image/gif');
        }
        abort(404);
    }

    /**
     * /file/{fileType}/{id}
     */
    public function deleteFile($type, $id, $ext)
    {

        switch ($type) {

            case 'profile':

                $delete = array(
                    str_replace('public/', '', PROFILE_IMAGE_PATH) . "/thumbs/{$id}.{$ext}",
                    str_replace('public/', '', PROFILE_IMAGE_PATH) . "/{$id}.{$ext}",
                );
                break;
            default:
                return response()->json([
                    'message' => 'Invalid type',
                ], 500);
        }
        Storage::disk('public')->delete($delete);

        return response()->api([
            'message' => 'Deleted',
        ], 200);
    }
}
