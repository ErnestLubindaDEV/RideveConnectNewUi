<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class WebsiteController extends BaseController
{

    public function services()
    {
        return view('Website.services');
    }
    public function events()
    {
        return view('Website.events');
    }

    public function blog()
    {
        return view('Website.blog-grid');
    }

    public function blog_details()
    {
        return view('website.blog_details');
    }

}


