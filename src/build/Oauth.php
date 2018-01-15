<?php
/**
 * Author: xupp
 * Date: 2016/12/27
 * Time: 14:44
 */

namespace wechat\build;

use wechat\Wx;

class Oauth extends Wx{

    public function request($scope = 'snsapi_userinfo', $referer = ''){
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            $url = $this->apiUrl."/sns/oauth2/access_token?appid=".self::$config['appId']."&secret=".self::$config['appSecret']."&code={$code}&grant_type=authorization_code";
            $data = $this->curl($url);
            return $this->get($data);
        }else{

            if(!$referer){
                $referer = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            }
            $redirect_uri = urlencode($referer);
            $code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::$config['appId']."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=STATE#wechat_redirect";
            header("Location: $code_url");
            exit;
        }
    }
    public function snsapi_base(){
        $data = $this->request('snsapi_base');

        return $data ? $data : false;
    }

    public function snsapi_userinfo($referer = ''){
        $data = $this->request('snsapi_userinfo', $referer);
        if(isset($data['errcode'])){
            return false;
        }
        $url = $this->apiUrl."/sns/userinfo?access_token={$data['access_token']}&openid={$data['openid']}";
        $result = $this->curl($url);
        return $this->get($result);
    }
}