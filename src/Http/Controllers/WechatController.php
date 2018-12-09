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

class OrderController extends Controller
{

    public function uuid()
    {
        return md5(uniqid(rand(), true));
    }

}
