<?php
namespace app\index\controller;

use think\Controller;
use think\Session;

class Index extends Controller
{
    public function index()
    {
        if(session('has_login') !== true){
            $this->redirect('index/login/login');
        }else{
            return $this->fetch();
        }
    }
}
