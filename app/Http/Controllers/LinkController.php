<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\LinkVisitor;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //links with pagination
        $links = auth()->user()->links()->paginate(10);
        return view('links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) {
                    //check if the original url already exists
                    if (Link::where('original_url', $value)->where('user_id', auth()->id())->exists()) {
                        $fail('The original URL already exists.');
                    }
                },
            ],
        ]);

        //generate short code
        $shortCode = $this->generateRandomString();

        //check if the shortened url already exists until it doesn't
        while (Link::where('generated_code', $shortCode)->exists()) {
            $shortCode = $this->generateRandomString();
        }

        //create insert the link
        $link = auth()
            ->user()
            ->links()
            ->create([
                'original_url' => $request->input('original_url'),
                'generated_code' => $shortCode,
            ]);

        //generate link short url
        $currentLink = $request->getSchemeAndHttpHost();
        $shortUrl = $currentLink . '/' . $shortCode;

        //set urls in session
        session()->put('original_url', $request->input('original_url'));
        session()->put('short_url', $shortUrl);

        //set success message
        return redirect()->back()->withSuccess('Link created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $link = Link::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$link) {
            return redirect()->route('home')->withError('Link not found!');
        }

        $totalVisits = $link->visitors()->count();
        $uniqueVisitors = $link->visitors()->distinct('visitor_ip')->count('visitor_ip');
        $visitorDetails = $link->visitors()->get();

        return view('links.show', compact('link', 'totalVisits', 'uniqueVisitors', 'visitorDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = Link::find(['id' => $id])
            ->where('user_id', auth()->id())
            ->first();

        if (!$link) {
            return redirect()->back()->withError('Link not found!');
        }

        $link->delete();

        return redirect()->back()->withSuccess('Link deleted successfully!');
    }

    /**
     * Custom functions
     */

    public function redirect($code)
    {
        $link = Link::where('generated_code', $code)->first();

        if (!$link) {
            return redirect()->route('home')->withError('Link not found!');
        }

        LinkVisitor::create([
            'link_id' => $link->id,
            'visitor_ip' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'referrer' => request()->header('referer') ?? null,
        ]);

        return redirect($link->original_url);
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
}
