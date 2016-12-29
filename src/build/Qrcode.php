<?php
/**
 * Author: xupp
 * Date: 2016/12/27
 * Time: 9:26
 */

namespace wechat\build;

use wechat\Wx;

class Qrcode extends Wx{
    /**
     * @param $scene_id  场景id
     * @param $expire_time  过期时间 大于0为永久二维码，否则为临时二维码
     * @return mixed 返回ticket
     */
    public function create($scene_id,$expire_time = 0){
        if($expire_time){
            $url = $this->apiUrl.'/cgi-bin/qrcode/create?access_token='.$this->getAccessToken();
            $data = '{
                        "expire_seconds": '.$expire_time.',
                        "action_name": "QR_SCENE",
                        "action_info": {
                            "scene": {
                                "scene_id": '.$scene_id.'
                            }
                        }
                    }';
        }else{
            $url = $this->apiUrl.'/cgi-bin/qrcode/create?access_token='.$this->getAccessToken();
            $data = '{
                        "action_name": "QR_LIMIT_SCENE",
                        "action_info": {
                            "scene": {
                                "scene_id": '.$scene_id.'
                                }
                        }
                    }';
        }
        $result = $this->curl($url,$data);
        $ticket = $this->get($result);
        if(!isset($ticket['errcode'])){
            return $ticket['ticket'];
        }
    }


    public function getQrcode($ticket){
        if(!$ticket){
            return false;
        }
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
        $result = $this->curl($url);
        return $this->get($result);
    }
}