<?php

namespace app\index\controller;

use app\index\model\Yingjian;
use think\Controller;
use think\Exception;

Class Search extends Controller
{
    /**
     * @return \think\response\Json
     */
    const PLACE_N = 3;

    /**
     * @return \think\response\Json
     */
    public function read_huanjing()
    {
        $type = $_GET['type'];
        $response = ['status' => 'ok'];

        if (session('has_login') === null) {
            $response['status'] = 'error, please login';
            return json($response);
        }

        $Huanjing = D('Huanjing');
        $huanjing_info = [];
        for ($i = 1; $i <= self::PLACE_N; $i++) {
            $placename = ''.$i;
            try {
                if(isset($type)) {
                    $huanjing_info = $Huanjing->query('SELECT $type FROM huanjing WHERE place = $placename ORDER BY time desc LIMIT 1');
                }
                else{
                    $huanjing_info = $Huanjing->query('SELECT * FROM huanjing WHERE place = $placename ORDER BY time desc LIMIT 1');
                }
            } catch (Exception $e) {
                $response = ['status' => 'error, sql error'];
                return json($response);
            }
            if (!$huanjing_info) {
                continue;
            }
            $response[$placename] = $huanjing_info;
            return json($response);
        }
    }
    //  以管理员的身份登录，查看硬件信息
    public function read_yingjian()
    {
        $type = $_GET['type'];
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
            $Yingjian = D('Yingjian');
            $yingjian_info = [];
            for ($i = 1; $i <= self::PLACE_N; $i++) {
                $placename = ''.$i;
                try {
                    if(isset($type)) {
                        $yingjian_info = $Yingjian->query('SELECT $type FROM yingjian WHERE place = $placename ORDER BY time desc LIMIT 10');
                    }
                    else{
                        $yingjian_info = $Yingjian->query('SELECT * FROM yingjian WHERE place = $placename ORDER BY time desc LIMIT 10');
                    }
                } catch (Exception $e) {
                    $response = ['status' => 'error, sql error'];
                    return json($response);
                }
                $response[$placename] = $yingjian_info;
                return json($response);
            }
        }
    }
}

?>
