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

class WechatController extends Controller
{

    public function echo(Request $request)
    {
        $echoStr = $_GET["echostr"];
        return $echoStr;
//        if($this->checkSignature()){
//        }
    }

    public function info(Request $request)
    {
        return view('wechat::info');
    }
}
