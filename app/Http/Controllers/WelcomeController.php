<?php

namespace App\Http\Controllers;

use App\Models\Media\Instagram\InstagramPost;
use App\Models\Media\Vk\VkPost;
use App\Models\Media\Youtube\YoutubeVideo;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    protected $perPageVk = 3;
    protected $perPageInstagram = 3;
    protected $perPageYoutube = 3;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPageVk = $this->perPageVk;
        $perPageInstagram = $this->perPageInstagram;
        $perPageYoutube = $this->perPageYoutube;


        return view('welcome', compact('perPageVk', 'perPageInstagram', 'perPageYoutube'));
    }
}
