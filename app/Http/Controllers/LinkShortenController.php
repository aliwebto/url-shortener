<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

class LinkShortenController extends Controller
{
    // show index page
    public function index()
    {
        return view('index');
    }

    // redirect function
    public function redirect($hash)
    {
        $url = Url::where('shortHash',$hash)->firstOrFail();

        // add 1 view count
        $url->update([
            'viewCount' => $url->viewCount + 1
        ]);
        return redirect($url->url);
    }
}
