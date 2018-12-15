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
use Laravel\Passport\WechatOrder;

/**
 * Class OrderController
 * @package Bitmyth\Wechat\Http\Controllers
 */
class OrderController extends Controller
{
    use WechatOrder;
    /**
     * @param Request $request
     * @return mixed
     */
    public function echo(Request $request)
    {
        $echoStr = $_GET["echostr"];
        return $echoStr;
//        if($this->checkSignature()){
//        }
    }


}
