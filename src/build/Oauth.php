<?php
/** .-------------------------------------------------------------------
 * |      Site: xupp.cn
 * |-------------------------------------------------------------------
 * |    Author: xupp <390241457@qq.com>
 * |    Date: 2018/9/5
 * | Copyright (c) 2015-2017, xupp.cn All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace wechat\build;

use wechat\Wx;

class Oauth extends Wx{
    /**
     * 公众号网页授权获取用户信息
     * @param string $scope
     * @return array|bool
     */
    public function getOpenId($scope = 'snsapi_base'){
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            $url = $this->apiUrl."/sns/oauth2/access_token?appid=".self::$config['appId']."&secret=".self::$config['appSecret']."&code={$code}&grant_type=authorization_code";
            $data = $this->curl($url);
            return $this->get($data);
        }else{
            $back_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'];
            $redirect_uri = urlencode($back_url);
            $code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::$config['appId']."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=STATE&connect_redirect=1#wechat_redirect";
            header("Location: $code_url");
            exit;
        }
    }

    /**
     * 小程序获取用户信息
     * @param $code
     * @return bool
     */
    public function getOpenIdByMini($code) {
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.self::$config['appId'].'&secret='.self::$config['appSecret'].'&js_code='.$code.'&grant_type=authorization_code';
        $result = $this->curl($url);
        $data = $this->get($result);
        return $data ? $data['openid'] : false;
    }

    public function snsapi_base(){
        $data = $this->getOpenId('snsapi_base');
        return $data ? $data['openid'] : false;
    }

    public function snsapi_userinfo(){
        $cachename = md5(self::$config['appSecret'].self::$config['appId']);
        $file = __DIR__ . '/../cache/'.$cachename.'.php';
        if(is_file($file) && filemtime($file) + 7000 > time()){
            $data = include $file;
        }else{
            $data = $this->getOpenId('snsapi_userinfo');
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