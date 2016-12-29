<?php
/**
 * Author: xupp
 * Date: 2016/12/27
 * Time: 12:47
 */

namespace wechat\build;

use wechat\Wx;

class Custom extends Wx{
    protected $url;
    public function __construct(){
        parent::__construct();
        $this->url = $this->apiUrl.'/cgi-bin/message/custom/send?access_token='.$this->getAccessToken();
    }
    //添加客服账号
    public function addCustom($account,$nickname,$password){
        $url = $this->apiUrl.'/customservice/kfaccount/add?access_token='.$this->getAccessToken();
        $data = '{
                     "kf_account" : "'.$account.'",
                     "nickname" : "'.$nickname.'",
                     "password" : "'.md5($password).'",
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //修改客服帐号
    public function updateCustom($account,$nickname,$password){
        $url = $this->apiUrl.'/customservice/kfaccount/update?access_token='.$this->getAccessToken();
        $data = '{
                     "kf_account" : "'.$account.'",
                     "nickname" : "'.$nickname.'",
                     "password" : "'.md5($password).'",
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //删除客服帐号
    public function deleteCustom($account,$nickname,$password){
        $url = $this->apiUrl.'/customservice/kfaccount/del?access_token='.$this->getAccessToken();
        $data = '{
                     "kf_account" : "'.$account.'",
                     "nickname" : "'.$nickname.'",
                     "password" : "'.md5($password).'",
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //上传客服头像
    public function uploadHeadImg($file,$account){
        $url = $this->apiUrl.'/customservice/kfaccount/uploadheadimg?access_token='.$this->getAccessToken().'&kf_account='.$account;
        $file = realpath($file);//将文件转为绝对路径
        if(class_exists('\CURLFile', false )){
            $data = [
                'media' => new \CURLFile($file)
            ];
        }else{
            $data = [
                'media' => '@'.$file
            ];
        }
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //获取所有客服账号
    public function getKfList(){
        $url = $this->apiUrl.'/cgi-bin/customservice/getkflist?access_token='.$this->getAccessToken();
        $result = $this->curl($url);
        return $this->get($result);
    }
     /*===============发送客服消息===============*/
    //文本消息
    public function sendText($openid,$content){
        $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"text",
                    "text":
                    {
                         "content":"'.$content.'"
                    }
                }';
        $result = $this->curl($this->url,$data);
        return $this->get($result);
    }
    //图片消息
    public function sendImage($openid,$media_id){
        $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"image",
                    "image":
                    {
                      "media_id":"'.$media_id.'"
                    }
                }';
        $result = $this->curl($this->url,$data);
        return $this->get($result);
    }
    //语音消息
    public function sendVoice($openid,$media_id){
        $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"voice",
                    "voice":
                    {
                      "media_id":"'.$media_id.'"
                    }
                }';
        $result = $this->curl($this->url,$data);
        return $this->get($result);
    }
    //视频消息
    public function sendVideo($openid,$media_id,$title,$description,$thumb_media_id=''){
        $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"video",
                    "video":
                    {
                      "media_id":"'.$media_id.'",
                      "thumb_media_id":"'.$thumb_media_id.'",
                      "title":"'.$title.'",
                      "description":"'.$description.'"
                    }
                }';
        $result = $this->curl($this->url,$data);
        return $this->get($result);
    }
    //音乐消息
    public function sendMusic($openid,$title,$description,$musicurl,$hqmusicurl='',$thumb_media_id=''){
        $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"music",
                    "music":
                    {
                      "title":"'.$title.'",
                      "description":"'.$description.'",
                      "musicurl":"'.$musicurl.'",
                      "hqmusicurl":"'.$hqmusicurl.'",
                      "thumb_media_id":"'.$thumb_media_id.'"
                    }
                }';
        $result = $this->curl($this->url,$data);
        return $this->get($result);
    }
    //图文消息
    public function sendNews($openid,$newsArr){
        if(!is_array($newsArr)){
            return '';
        }
        $temp = '';
        foreach((array) $newsArr as $item){
            $temp .= '{
                     "title":"'.$item['title'].'",
                     "description":"'.$item['description'].'",
                     "url":"'.$item['url'].'",
                     "picurl":"'.$item['picurl'].'"
                 },';
        }

        $data = '{
                    "touser":"'.$openid.'",
                    "msgtype":"news",
                    "news":{
                        "articles": [
                         '.$temp.'
                         ]
                    }
                }';
        $result = $this->curl($this->url,$data);
        return $this->get($result);
    }
}