<?php

namespace app\index\controller;

use think\Controller;
use think\Exception;

Class Search extends Controller{
    /**
     * @return \think\response\Json
     */
    const PLACE_N = 3;

    /**
     * @return \think\response\Json
     */
    public function read_huanjing()
    {
        $response = ['status' => 'ok'];

        if (session('has_login') === null) {
            $response['status'] = 'error, please login';
            return json($response);
        }

        $Huanjing = M('Huanjing');
        $huanjing_info = [];
        for ($i = 0; $i <self::PLACE_N;$i++){
            $placename = '' . $i;
            try {
                $temp = $Huanjing->query('SELECT * FROM huanjing WHERE place = $placename ORDER BY time LIMIT 1');
            } catch (Exception $e) {
                $response = ['status' => 'sql is error'];
                return json($response);
            }
            if (!$temp) {
                continue;
            }
            $huanjing_info[$placename]['time'] = $temp['time'];
            $huanjing_info[$placename]['temp'] = $temp['temp'];
            $huanjing_info[$placename]['humi'] = $temp['humi'];
            $huanjing_info[$placename]['PM2.5'] = $temp['PM2.5'];
            $huanjing_info[$placename]['place'] = $temp['place'];
            $huanjing_info[$placename]['gps'] = $temp['gps'];
            $response['huanjing_info'] = $huanjing_info;
            return json($response);
         }
    }
}

?>
