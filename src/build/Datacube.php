<?php
/** .-------------------------------------------------------------------
 * |      Site: xupp.cn
 * |-------------------------------------------------------------------
 * |    Author: xupp <390241457@qq.com>
 * |    Created by xupp on 2017/4/6.
 * | Copyright (c) 2015-2017, xupp.cn All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace wechat\build;

use wechat\Wx;

/**
 * 数据统计
 * Class Datacube
 * @package wechat\build
 */
class Datacube extends Wx{

    /**
     * 用户数据分析
     * @param string $beginDate 开始时间
     * @param string $endDate 结束时间
     * @return array|bool
     */
    public function getUserSummary($beginDate = '',$endDate = ''){
        if(strtotime($beginDate) > strtotime($endDate)){
            return false;
        }
        $date = date('Y-m-d',strtotime('+6 day',strtotime($beginDate)));
        if(strtotime($endDate) > strtotime($date)){
            $endDate = $date;
        }
        $url = $this->apiUrl.'/datacube/getusersummary?access_token='.$this->getAccessToken();
        $data = '{
            "begin_date" : "'.$beginDate.'",
            "end_date" : "'.$endDate.'"
        }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
}