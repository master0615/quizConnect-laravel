<?php

namespace App\Http\Controllers;

use App\ProfilePhoto;
use App\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

class ProfilePhotoController extends Controller
{
    public function upload(Request $request, $userId)
    {
		$request->validate([
            'name' => 'required',
			'photo' => 'required|mimes:jpg,jpeg,png',
        ], [
		]);


        $user = User::findOrFail($userId);

        $is_exist = isset($user['id']) && ProfilePhoto::where("user_id", $user->id)->exists();

        if ( $is_exist ) {
            $profile_photo = $user->profile_photo;
            $delete = array(
                str_replace('public/', '', PROFILE_IMAGE_PATH) . "/{$profile_photo->id}.{$profile_photo->ext}",
                str_replace('public/', '', PROFILE_IMAGE_PATH) . "/thumbs/{$profile_photo->id}.{$profile_photo->ext}",
            );
    
            // Delete related files
            Storage::disk('public')->delete($delete);
        } else {
            $profile_photo = new ProfilePhoto();
        }

        $mimeType = $request->photo->getMimeType();
        switch ($mimeType) {
            case 'image/jpeg':
                $ext = 'jpg';
                break;
            case 'image/png':
                $ext = 'png';
                break;
            default:
                throw new \App\Exceptions\InvalidMimeException();
        }

        $profile_photo->user_id = $userId;
        $profile_photo->name = $request->name;
        $profile_photo->ext = $ext;
        $profile_photo->save();

        // store on disk

        $photo   = $request->file('photo');      
        $resize = Image::make($photo)->fit(PROFILE_IMAGE_SIZE)->encode($ext);

        $targetFile = "{$profile_photo->id}.{$ext}";
        //$path = Storage::putFileAs( PROFILE_IMAGE_PATH,  $photo, $targetFile);
        $path = Storage::put( PROFILE_IMAGE_PATH . '/' . $targetFile,  $resize->__toString());

        //--save thumb
        $resize_thumb = Image::make($photo)->fit(PROFILE_IMAGE_THUMB_SIZE)->encode($ext);
        //$path = Storage::putFileAs( PROFILE_IMAGE_PATH . '/thumbs',  $photo, $targetFile);
        $path_thumb = Storage::put( PROFILE_IMAGE_PATH . '/thumbs/' . $targetFile,  $resize_thumb->__toString() );

        $profile_photo->path = $profile_photo->path();
        $profile_photo->thumb = $profile_photo->thumb();

        return response()->json( $profile_photo );
    }

    public function show($userId) 
    {
        $user = User::findOrFail($userId);

        $profile_photo = $user->profile_photo;

        if (isset($profile_photo) ) {
            $profile_photo->path = $profile_photo->path();
            $profile_photo->thumb = $profile_photo->thumb();
        }

        return response()->json($profile_photo);
    }

    public function rotate($userId, $degree)
    {
        if (!in_array($degree, ['90', '180', '270'])) {
            throw new \App\Exceptions\NotAllowedException();
        }

        $user = User::findOrFail($userId);

        $is_exist = isset($user['id']) && ProfilePhoto::where("user_id", $user->id)->exists();
        $profile_photo = $user->profile_photo;

        if ( $is_exist ) {
            $targetFile =  "{$profile_photo->id}.{$profile_photo->ext}";
            $target = str_replace('public/', '', PROFILE_IMAGE_PATH) . "/{$targetFile}";
            $target_thumb = str_replace('public/', '', PROFILE_IMAGE_PATH) . "/thumbs/{$targetFile}";
            $file = Storage::disk('public')->get($target);;
            $file_thumb = Storage::disk('public')->get($target_thumb);;
            //return response()->json( $target );
            $rotate_img = Image::make($file)->rotate($degree)->encode($profile_photo->ext);
            $rotate_img_thumb = Image::make($file_thumb)->rotate($degree)->encode($profile_photo->ext);

            $path = Storage::put( PROFILE_IMAGE_PATH . '/' . $targetFile,  $rotate_img->__toString());
            $path_thumb = Storage::put( PROFILE_IMAGE_PATH . '/thumbs/' . $targetFile,  $rotate_img_thumb->__toString());

            $profile_photo->path = $profile_photo->path();
            $profile_photo->thumb = $profile_photo->thumb();

        }
    
        return response()->json( $profile_photo );
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProfilePhoto  $profilePhoto
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);

        $is_exist = isset($user['id']) && ProfilePhoto::where("user_id", $user->id)->exists();
        if ( $is_exist ) {
            $profile_photo = $user->profile_photo;
            $delete = array(
                str_replace('public/', '', PROFILE_IMAGE_PATH) . "/{$profile_photo->id}.{$profile_photo->ext}",
                str_replace('public/', '', PROFILE_IMAGE_PATH) . "/thumbs/{$profile_photo->id}.{$profile_photo->ext}",
            );
    
            // Delete related files
            Storage::disk('public')->delete($delete);

            $profile_photo->delete();
        }

    return response()->json( null, 204 );
    }
}
