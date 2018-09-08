<?php
/**
 * Created by PhpStorm.
 * User: chenlei
 * Date: 2018/6/25
 * Time: 21:16
 */

namespace app\index\controller;


use think\Controller;
use think\Exception;
use think\Validate;

class Switches extends Controller
{
//    与数据库 内数据形式保持一致
    private function for_database($n)
    {
        if ($n === 'true' || $n === true)
            $n = 1.0;
        else
            $n = 0.0;
        return $n;
    }

//        设置开关
    public function onOff()
    {
        date_default_timezone_set("Asia/Shanghai"); //设置时区为中国标准，不然下面取时间会以服务器时间为准
        $response = ['status' => 'ok'];
        if (session('has_login') === null) {
            $response['status'] = 'error, please login';
            return json($response);
        }
        if (!(isset($_POST['dengguang']) && isset($_POST['chuanglian'])
            && isset($_POST['menjin']) && isset($_POST['place']))) {
            $response['status'] = 'error';
            return json($response);
        }

        //验证器 验证
        $validate = new Validate([
            'dengguang' => 'require',
            'chuanglian' => 'require',
            'menjin' => 'require',
            'place' => 'require|number|token'
        ]);

        if($validate->check($_POST) === false){
            $response['status'] =  'error';
            return json($response);
        }

        $command = model('Command');
        $command->data([
            'command_dengguang' => $this->for_database($_POST['dengguang']),
            'command_chuanglian' => $this->for_database($_POST['chuanglian']),
            'command_menjin' => $this->for_database($_POST['menjin']),
            'place' => date($_POST['place']),
            'time' => date('y-m-d h:i:s')
        ]);
        try {
            $command->save();
            return json($response);
        } catch (Exception $e) {
            $response['status'] = 'error';
            return json($response);
        }
    }

    const PLACE_N = 3;

    public function read_switches()
    {
        $response = ['status' => 'ok'];
        if (session('has_login') === null) {
            $response['status'] = 'error, please login';
            return json($response);
        }
        $switches_info = [];
        $command = model('command');
        for ($i = 0; $i <= self::PLACE_N; $i++) {
            $place_name = '' . $i;
            try {
                $temp = $command->where('place', $place_name)
                    ->order('time', 'desc')
                    ->limit(1)
                    ->find();
            } catch (Exception $e) {
                $response['status'] = 'error, sql error';
                return json($response);
            }
            if ($temp === null)
                continue;
            $switches_info[$place_name]['place'] = $temp['place'];
            $switches_info[$place_name]['time'] = $temp['time'];
            $switches_info[$place_name]['dengguang'] = (int)$temp['command_dengguang'];
            $switches_info[$place_name]['chuanglian'] = (int)$temp['command_chuanglian'];
            $switches_info[$place_name]['menjin'] = (int)$temp['command_menjin'];
        }
        $response['switches_info'] = $switches_info;
        return json($response);
    }
}