<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiKey;
use App\Models\Link;

class ApiController extends Controller
{
    public function index()
    {

        $apiKey = auth()->user()->apiKey ?? '';

        return view('api.index', compact('apiKey'));
    }

    public function generate()
    {
        //re generate api key
        $apiKey = $this->generateRandomString();

        if (auth()->user()->apiKey) {
            //update api key
            auth()
                ->user()
                ->apiKey()
                ->update([
                    'key' => $apiKey,
                    'expires_at' => now()->addYear(),
                ]);
        } else {
            //create api key
            auth()
                ->user()
                ->apiKey()
                ->create([
                    'key' => $apiKey,
                    'expires_at' => now()->addYear(),
                ]);
        }

        return redirect()->back()->withSuccess('API key generated successfully!');
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function short(Request $request)
    {
        $url = $request->input('url');

        //check if valid url
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['message' => 'Invalid URL.'], 400);
        }

        $apiKey = $request->input('api_key');

        if (!$url) {
            return response()->json(['message' => 'Please provide an original URL.'], 400);
        }

        if (!$apiKey) {
            return response()->json(['message' => 'Please provide an API key.'], 400);
        }


        $apiKey = ApiKey::where('key', $apiKey)->first();


        if (!$apiKey) {
            return response()->json(['message' => 'Invalid API key.'], 400);
        }

        $link = Link::where('original_url', $url)->where('user_id', $apiKey->user_id)->first();


        if ($link) {
            return response()->json(['message' => 'Shortened URL already exists.'], 400);
        }

        $link = Link::create([
            'original_url' => $url,
            'user_id' => $apiKey->user_id,
            'generated_code' => $this->generateRandomString(),
        ]);

        $shortenedUrl = url($link->generated_code);

        return response()->json(['message' => 'Shortened URL created successfully.', 'generated_url' => $shortenedUrl], 200);

    }
}
