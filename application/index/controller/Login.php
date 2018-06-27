<?php
/**
 * Created by PhpStorm.
 * User: chenlei
 * Date: 2018/6/25
 * Time: 20:41
 */

namespace app\index\controller;

use think\Controller;
use think\Session;

class Login extends Controller
{
    public function login()
    {
        if (isset($_POST['account']) && isset($_POST['pwd'])) {
            $response = ['status' => 'ok', 'is_pass' => false];
            $account = $_POST['account'];
            $pwd = $_POST['pwd'];
            if ($account != 'root') {
                $response['is_pass'] = false;
            }else{
                if ($pwd==='root'){
                    $response['is_pass'] = true;
                    session('has_login',true);
                }else{
                    $response['is_pass'] = false;
                }

            }
            return json($response);
        }
        return $this->fetch();
    }
}