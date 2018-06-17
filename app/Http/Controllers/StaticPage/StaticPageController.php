<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/17
 * Time: 17:43
 */

namespace App\Http\Controllers\StaticPage;


use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function help () {
        return view('static_pages/help');
    }

    public function about () {
        return view('static_pages/about');
    }
}