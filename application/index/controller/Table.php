<?php
/**
 * Created by PhpStorm.
 * User: chenlei
 * Date: 2018/6/26
 * Time: 21:27
 */

namespace app\index\controller;

use think\Controller;
use think\Exception;

class Table extends Controller
{
    const DATA_SIZE = 200;
    public function get_data(){
        $response = ['status' => 'ok'];
        if (session('has_login') === null) {
            $response['status'] = 'error, please login';
            return json($response);
        }
        if (!(isset($_GET['type']))){
            $response['status'] = 'error';
            return json($response);
        }
        $type = $_GET['type'];
        $huan_jing = model('huanjing');
        try {
            $info_list = $huan_jing->order('time', 'desc')
                ->limit(self::DATA_SIZE)->column('time,' . $type);
            $info_list = array_reverse($info_list);
            $response['x'] = array_keys($info_list);
            $response['y'] = array_values($info_list);
        }catch(Exception $e){
            $response['status'] = 'error';
            return json($response);
        }

        return json($response);
    }

    public function get_current_data(){
        $response = ['status' => 'ok'];
        if (session('has_login') === null) {
            $response['status'] = 'error, please login';
            return json($response);
        }

        $huan_jing = model('huanjing');
        try {
            $info = $huan_jing->order('time', 'desc')->find();
            $response['info'] = $info;
        }catch(Exception $e){
            $response['status'] = 'error';
        }
        return json($response);
    }

//    前端应该用不到，给硬件端用的
    public function write_data()
    {
        $response = ['status' => 'ok'];
        if (session('has_login') === null) {
            $pass = false;
            if (isset($_POST['account']) && isset($_POST['pwd'])) {
                if ($_POST['pwd'] === config('pwd') &&
                    $_POST['account'] === config('account')) {
                    $pass = true;
                }
            }
            if ($pass === false) {
                $response['status'] = 'error, please login';
                return json($response);
            }
        }
        $huan_jing = model('huanjing');
        $huan_jing->data($_POST);
        try {
            $huan_jing->allowField(true)->save(); // allowField函数过滤掉非数据表的字段
            $response['status'] = 'ok';
            return json($response);
        }catch(Exception $e){
            $response['status'] = 'error, sql error';
            return json($response);
        }
    }
}