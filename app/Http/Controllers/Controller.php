<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

define('PUBLIC_DIR', \Illuminate\Support\Facades\Storage::disk('public')->getDriver()
        ->getAdapter()
        ->getPathPrefix());
        
define('PROFILE_IMAGE_PATH', 'public/images/profile');
define('PROFILE_IMAGE_SIZE', '200');
define('PROFILE_IMAGE_THUMB_SIZE', '50');

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
