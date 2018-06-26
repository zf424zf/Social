<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/17
 * Time: 17:40
 */

namespace App\Http\Controllers;


class HomeController extends Controller
{
    public function index(){
        $feed_items = [];
        if (\Auth::check()) {
            $feed_items = \Auth::user()->feed()->paginate(30);
        }
        return view('home',compact('feed_items'));
    }
}