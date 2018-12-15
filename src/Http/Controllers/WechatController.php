<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/8
 * Time: 9:46 PM
 */

namespace Bitmyth\Wechat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class WechatController
 * @package Bitmyth\Wechat\Http\Controllers
 */
class WechatController extends Controller
{

    /**
     * @return string
     */
    public function uuid()
    {
        return md5(uniqid(rand(), true));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(Request $request)
    {
        return view('wechat::info');
    }
}
