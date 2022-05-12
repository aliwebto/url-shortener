<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LinkShortenController extends Controller
{
    private array $response = ['response' => '', 'success' => false];

    private function handleValidate($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $this->response['response'] = $validator->messages();
            return response($this->response, 400);
        }
        return true;
    }

    // create unique hash for short link
    private function createShortHash()
    {
        do {
            $hash = Str::random(8);
        } while (!! Url::where('shortHash',$hash)->first());
        return $hash;
    }

    public function create(Request $request)
    {
        $validator = $this->handleValidate($request, ['url' => 'required|url|max:250']);
        if ($validator !== true) {
            return $validator;
        }

        // save url to DB
        $full_url = $request->get('url');
        $url = Url::create([
            'url' => $full_url,
            'shortHash' => $this->createShortHash()
        ]);


        // create response array
        $link = route('link-shorten.redirect',['hash' => $url->shortHash]);
        $this->response['response'] = [
            'link' =>  $link,
            'full_link' => $full_url
        ];
        $this->response['success'] = true;

        // return response
        return response()->json($this->response);
    }
}
