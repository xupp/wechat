<?php
/**
 * Author: xupp
 * Date: 2016/12/26
 * Time: 9:29
 */

namespace wechat;

class Wx extends Error{

    public static $config = [];//配置项
    protected $object;//微信发过来的内容
    protected $apiUrl = 'https://api.weixin.qq.com';//公众平台接口域名
    public $access_token;//access_token是公众号的全局唯一接口调用凭据，公众号调用各接口时都需使用access_token


    public function __construct(array $config = []){
        if(!empty($config)){
            self::$config = $config;
        }
        $this->object = $this->parsePostRequestData();
    }

    //验证消息的确来自微信服务器
    public function valid(){
        if(isset($_GET['echostr'])){
            $signature = $_GET['signature'];
            $timestamp = $_GET['timestamp'];
            $nonce = $_GET['nonce'];
            $token = self::$config['token'];
            $tmpArr = [$token,$timestamp,$nonce];
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);
            if($tmpStr == $signature){
                echo $_GET['echostr'];
                exit;
            }
        }
    }

    //获取并解析用户传过来的数据
    private function parsePostRequestData(){
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) && !empty($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        if($this->xml_parse($postStr)){
            if(!empty($postStr)){
                return simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
            }
        }
    }

    public function getObject(){
        return $this->object;
    }

    //获取access_token
    public function getAccessToken(){
        //缓存access_token
        $cachename = md5(self::$config['appId'].self::$config['appSecret']);
        $file = __DIR__.'/cache/'.$cachename.'.php';

        if(is_file($file) && filemtime($file) + 7000 > time()){
            $data = include $file;
        }else{
            $url = $this->apiUrl.'/cgi-bin/token?grant_type=client_credential&appid='.self::$config['appId'].'&secret='.self::$config['appSecret'];
            $data = $this->curl($url);
            if(isset($data['errcode'])){
                return false;
            }

            if(!is_dir(dirname($file))){
                mkdir(dirname($file),0777,true);
            }

            file_put_contents($file,"<?php return \r\n".var_export($data,true).";\r\n?>");
        }
        return $data['access_token'];
    }

    /**
     * curl请求 $field不为空时为post请求
     * @param $url 请求地址
     * @param array $fields post数据
     * @return mixed|string 返回数据
     */
    public function curl($url, $fields = []){
        $ch = curl_init(); //curl初始化
        curl_setopt($ch,CURLOPT_URL,$url); //设置请求地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//数据返回后不要直接显示
        //禁止证书校验
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        if($fields){
            curl_setopt($ch,CURLOPT_TIMEOUT,30);//响应时间为30秒
            curl_setopt($ch,CURLOPT_POST,1);//设为post请求
            curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);//post数据
        }
        $data = '';
        if(curl_exec($ch)){
            if(curl_errno($ch)){
                echo 'Curl error: ' . curl_error($ch);
                exit;
            }
            //发送成功，获取数据
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);
        $curlData = json_decode($data,true);
        if(is_array($curlData)){
            return $curlData;
        }else{
            return $data;
        }
    }

    private function xml_parse($str){
        $xml_parse = xml_parser_create();
        if(!xml_parse($xml_parse,$str,true)){
            xml_parser_free($xml_parse);
            return false;
        }else{
            return true;
        }
    }

    //获取功能实例
    public function instance($name){
        $class = '\wechat\build\\' . ucfirst($name);
        return new $class;
    }
}