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
        return view('home');
    }
}