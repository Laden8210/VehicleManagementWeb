<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($image, $path = 'public')
    {
        if($image)
        {
            return null;
        }

        $filename = time().'.png';
        //save image
        \Storage::disk($path)->puth($filename, base64_decode($image));

        //return the path
        //url is the base url exp: localhost:8000 in laravel
        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }
}
