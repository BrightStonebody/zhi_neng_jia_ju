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

class OnOff extends Controller
{
    private function for_database($n){
        if ($n === 'true' || $n === true)
            $n = 1.0;
        else
            $n = 0.0;
        return $n;
    }

    public function onOff()
    {
        date_default_timezone_set("Asia/Shanghai"); //设置时区为中国标准，不然下面取时间会以服务器时间为准
        $response = ['status' => 'ok'];
        if (session('has_login') === null) {
            $response['status'] = 'error, place login';
            return json($response);
        }
        if (!(isset($_POST['dengguang']) && isset($_POST['chuanglian'])
            && isset($_POST['menjin']) && isset($_POST['place']))) {
            $response['status'] = 'error';
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
            $response['status'] = 'database_error';
            return json($response);
        }
    }
}