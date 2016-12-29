<?php
/**
 * Author: xupp
 * Date: 2016/12/26
 * Time: 10:05
 */

namespace wechat\build;
use wechat\Wx;

class Message extends Wx{

    //用户事件消息类型
    const EVENT_TYPE_SUBSCRIBE = 'subscribe'; //关注
    const EVENT_TYPE_UNSUBSCRIBE = 'unsubscribe';//取消关注
    const EVENT_TYPE_CLICK = 'CLICK'; //菜单点击
    const EVENT_TYPE_SCAN = 'SCAN';//扫码
    const EVENT_TYPE_LOCATION = 'LOCATION'; //上传地理位置
    //用户发送消息类型
    const MSG_TYPE_TEXT = 'text';//文本
    const MSG_TYPE_IMAGE = 'image';//图片
    const MSG_TYPE_VOICE = 'voice';//声音
    const MSG_TYPE_VIDEO = 'video';//视频
    const MSG_TYPE_SHORT_VIDEO = 'shortvideo';//小视频
    const MSG_TYPE_LOCATION = 'location';//地理位置
    const MSG_TYPE_LINK = 'link';//链接

    //关注
    public function isSubscribe(){
        if($this->object->Event == self::EVENT_TYPE_SUBSCRIBE && $this->object->MsgType == 'event'){
            if(strlen($this->object->EventKey) == 0){
                return true;
            }else{
                $qrScene = explode('_',$this->object->EventKey);
                return $qrScene[1];
            }
        }
    }

    //取消关注
    public function isUnSubscribe(){
        return $this->object->Event == self::EVENT_TYPE_UNSUBSCRIBE && $this->object->MsgType == 'event';
    }

    //点击事件
    public function isClick(){
        return $this->object->Event == self::EVENT_TYPE_CLICK && $this->object->MsgType == 'event';
    }

    //用户已关注时的事件推送
    public function qrScene(){
        return $this->object->Event == self::EVENT_TYPE_SCAN;
    }

    //上传地理位置事件
    public function location(){
        if($this->object->Event == self::EVENT_TYPE_LOCATION){
            return $location = [
                    'Latitude' => $this->object->Latitude, //维度
                    'Longitude' => $this->object->Longitude, //经度
                    'Precision' => $this->object->Precision //精度
                ];
        }
    }

    //接收文本消息
    public function isTextMsg(){
        return $this->object->MsgType == self::MSG_TYPE_TEXT;
    }
    //接收图片消息
    public function isImageMsg(){
        return $this->object->MsgType == self::MSG_TYPE_IMAGE;
    }
    //接收语音消息
    public function isVoiceMsg(){
        return $this->object->MsgType == self::MSG_TYPE_VOICE;
    }
    //接收视频消息
    public function isVideoMsg(){
        return $this->object->MsgType == self::MSG_TYPE_VIDEO;
    }
    //接收小视频消息
    public function isShortVideoMsg(){
        return $this->object->MsgType == self::MSG_TYPE_SHORT_VIDEO;
    }
    //接收地理位置消息
    public function isLocationMsg(){
        return $this->object->MsgType == self::MSG_TYPE_LOCATION;
    }
    //接收链接消息
    public function isLinkMsg(){
        return $this->object->MsgType == self::MSG_TYPE_LINK;
    }

    //回复文本消息
    public function text($content){
        $xml = '<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                </xml>';
        $text = sprintf($xml, $this->object->FromUserName,$this->object->ToUserName,time(),$content);
        echo $text;
    }

    //回复图片消息
    public function image($mediaId){
        $xml = '<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Image>
                </xml>';
        $image = sprintf($xml, $this->object->FromUserName,$this->object->ToUserName,time(),$mediaId);
        echo $image;
    }

    //回复语音消息
    public function voice($mediaId){
        $xml = '<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <Voice>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>
                </xml>';
        $voice = sprintf($xml, $this->object->FromUserName,$this->object->ToUserName,time(),$mediaId);
        echo $voice;
    }

    //回复视频消息
    public function video($mediaId,$videoArr){
        if(!is_array($videoArr)){
            exit('');
        }
        $xml = '<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <Video>
                    <MediaId><![CDATA[%s]]></MediaId>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    </Video>
                </xml>';
        $voice = sprintf($xml, $this->object->FromUserName,$this->object->ToUserName,time(),$mediaId,$video['title'],$video['description']);
        echo $voice;
    }

    //回复音乐消息
    public function music($mediaId,$musicArr){
        if(!is_array($musicArr)){
            exit('');
        }
        $xml = '<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[music]]></MsgType>
                    <Music>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <MusicUrl><![CDATA[%s]]></MusicUrl>
                    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                    </Music>
                </xml>';
        $voice = sprintf($xml, $this->object->FromUserName,$this->object->ToUserName,time(),$musicArr['Title'], $musicArr['Description'], $musicArr['MusicUrl'], $musicArr['HQMusicUrl'],$mediaId);
        echo $voice;
    }

    //回复图文消息
    public function news($newsArr){
        if(!is_array($newsArr)){
            exit('');
        }
        $itemTpl = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>
        ";
        $item_str = "";
        foreach ($newsArr as $item){
            $item_str .= sprintf($itemTpl, $item['title'], $item['description'], $item['picUrl'], $item['url']);
        }
        $xml = '<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>'.$item_str.'</Articles>
                </xml>';
        $voice = sprintf($xml, $this->object->FromUserName,$this->object->ToUserName,time(),count($newsArr));
        echo $voice;
    }



    //点击事件消息
    public function click(){

    }
}