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
     * 创建二维码ticket
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

    /**
     * 通过ticket换取二维码
     * @param $ticket
     * @return array|bool
     */
    public function getQrcode($ticket){
        if(!$ticket){
            return false;
        }
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
        $result = $this->curl($url);
        return $this->get($result);
    }

    /**
     * 长连接转短链接
     * @param $shorturl
     * @return bool
     */
    public function shorturl($shorturl){
        if(!$shorturl){
            return false;
        }
        $url = $this->apiUrl.'/cgi-bin/shorturl?access_token='.$this->getAccessToken();
        $data = '{
                        "action": "long2short",
                        "long_url": '.$shorturl.'
                    }';
        $result = $this->curl($url,$data);
        $ticket = $this->get($result);
    }
}