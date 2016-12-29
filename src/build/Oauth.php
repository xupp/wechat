<?php
/**
 * Author: xupp
 * Date: 2016/12/27
 * Time: 14:44
 */

namespace wechat\build;

use wechat\Wx;

class Oauth extends Wx{
    public function request($scope = 'snsapi_userinfo'){
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            $url = $this->apiUrl."/sns/oauth2/access_token?appid=".self::$config['appId']."&secret=".self::$config['appSecret']."&code={$code}&grant_type=authorization_code";
            $data = $this->curl($url);
            return $this->get($data);
        }else{
            $back_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            $redirect_uri = urlencode($back_url);
            $code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::$config['appId']."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=STATE#wechat_redirect";
            header("Location: $code_url");
            exit;
        }
    }
    public function snsapi_base(){
        $data = $this->request('snsapi_base');
        return $data ? $data['openid'] : false;
    }

    public function snsapi_userinfo(){
        $cachename = md5(self::$config['appSecret'].self::$config['appId']);
        $file = './wechat/cache/'.$cachename.'.php';
        if(is_file($file) && filemtime($file) + 7000 > time()){
            $data = include $file;
        }else{
            $data = $this->request('snsapi_userinfo');
            if(isset($data['errcode'])){
                return false;
            }
            file_put_contents($file,"<?php return \r\n".var_export($data,true).";?>");
        }
        $url = $this->apiUrl."/sns/userinfo?access_token={$data['access_token']}&openid={$data['openid']}";
        $result = $this->curl($url);
        return $this->get($result);
    }
}