<?php

namespace App\Http\Controllers;

use App\Models\Media\Instagram\InstagramPost;
use App\Models\Media\Vk\VkPost;
use App\Models\Media\Youtube\YoutubeVideo;
use App\Models\Requests\RequestSite;
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
    public function index(Request $request)
    {
        $perPageVk = $this->perPageVk;
        $perPageInstagram = $this->perPageInstagram;
        $perPageYoutube = $this->perPageYoutube;

        $vk = VkPost::posts()->paginate($perPageVk);
        $instagram = InstagramPost::posts()->paginate($perPageInstagram);
        $youtube = YoutubeVideo::posts()->paginate($perPageYoutube);


        $vk = $vk->map(function ($item){
            return $item->getMedia();
        });
        $instagram = $instagram->map(function ($item){
            return $item->getMedia();
        });
        $youtube = $youtube->map(function ($item){
            return $item->getMedia();
        });
        $vk = json_encode($vk);
        $instagram = json_encode($instagram);
        $youtube = json_encode($youtube);

        if($request->ajax()){
            $response = response()->json(compact('vk', 'instagram', 'youtube', 'perPageVk', 'perPageInstagram', 'perPageYoutube'));
        }else{
            $response = view('welcome', compact('vk', 'instagram', 'youtube', 'perPageVk', 'perPageInstagram', 'perPageYoutube'));
        }

        return $response;
    }
    public function posts(Request $request)
    {
        $frd = $request->only(['social']);
        $perPageVk = $this->perPageVk;
        $perPageInstagram = $this->perPageInstagram;
        $perPageYoutube = $this->perPageYoutube;

        $res = [];

        if(isset($frd['social'])){
            if($frd['social'] === 'vk'){
                $posts = VkPost::posts()->paginate($perPageVk);
            }
            if($frd['social'] === 'instagram'){
                $posts = InstagramPost::posts()->paginate($perPageInstagram);
            }
            if($frd['social'] === 'youtube'){
                $posts = YoutubeVideo::posts()->paginate($perPageYoutube);
            }


            if(isset($posts)){
                $res = $posts->map(function ($item){
                    return $item->getMedia();
                });
            }
        }
        return response()->json($res);
    }

    public function requests(Request $request)
    {
        $messages = [
            'g-recaptcha-response.required'    => 'Обязательно пройдите reCAPTCHA',
            'g-recaptcha-response.captcha'    => 'reCAPTCHA не пройдена',
            'phone.min'    => 'Минимум 8 симоволов',
            'phone.required'    => 'Обязательно укажите телефон',
            'name.min'    => 'Минимум 2 симовола',
            'name.required'    => 'Как к вам обращаться?',
        ];
        \Validator::make($request->only(['name', 'phone', 'g-recaptcha-response']), [
            'name' => 'required|min:2',
            'phone' => 'required|min:8',
            'g-recaptcha-response' => 'required|captcha',

        ],$messages)->validate();

        $frd = $request->only(['name', 'phone']);
        $res = [
            'type'=>'success',
        ];
        RequestSite::create($frd);
        return response()->json($res);
    }

}
