<?php
/**
 * Author: xupp 群发消息
 * Date: 2016/12/30
 * Time: 9:48
 */

namespace wechat\build;

use wechat\Wx;

class Mpnews extends Wx{
    /**
     * 上传图文消息素材
     */
    public function uploadNews(array $newsArr)
    {
        if(empty($newsArr)){
            return false;
        }
        $temp = '';
        foreach((array) $newsArr as $item){
            $temp .= '{
                             "thumb_media_id":"'.$item['thumb_media_id'].'",
                             "author":"'.$item['author'].'",
                             "title":"'.$item['title'].'",
                             "content_source_url":"'.$item['content_source_url'].'",
                             "content":"'.$item['content'].'",
                             "digest":"'.$item['digest'].'",
                             "show_cover_pic":'.$item['show_cover_pic'].'
                         }';
        }
        $data = '{
                   "articles": [
                         '.$temp.'
                   ]
                }';
        $url = $this->apiUrl.'/cgi-bin/media/uploadnews?access_token='.$this->getAccessToken();
        $result = $this->curl($url,$data);
        return $this->get($result);
    }

    //根据标签进行群发
    public function sendAll($tag_id,$type,$content,$is_to_all = 'false',$send_ignore_reprint = 0){
        $url = $this->apiUrl.'/cgi-bin/message/mass/sendall?access_token='.$this->getAccessToken();
        $temp = '';
        switch($type){
            case 'mpnews' :
                $temp = '
                   "mpnews":{
                      "media_id":"'.$content.'"
                   },
                    "msgtype":"mpnews",';
                break;
            case 'text' :
                $temp = '{
                           "text":{
                              "content":"'.$content.'"
                           },
                            "msgtype":"text"
                        }';
                break;
            case 'voice' :
                $temp = '{
                           "voice":{
                              "media_id":"'.$content.'"
                           },
                            "msgtype":"voice"
                        }';
                break;
            case 'image' :
                $temp = '{
                           "image":{
                              "media_id":"'.$content.'"
                           },
                            "msgtype":"image"
                        }';
                break;
            case 'video' :
                $res = $this->uploadVideo($content);
                $temp = '{
                           "mpvideo":{
                              "media_id":"'.$res['media_id'].'"
                           },
                            "msgtype":"mpvideo"
                        }';
                break;
        }
        $data = '{
                   "filter":{
                      "is_to_all":'.$is_to_all.',
                      "tag_id":'.$tag_id.'
                   },
                   '.$temp.'
                    "send_ignore_reprint":'.$send_ignore_reprint.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }

    //根据OpenID列表群发
    public function send(array $openid_list,$type,$content,$is_to_all = 'false',$send_ignore_reprint = 0){
        $url = $this->apiUrl.'/cgi-bin/message/mass/send?access_token='.$this->getAccessToken();
        $temp = '';
        switch($type){
            case 'mpnews' :
                $temp = '
                   "mpnews":{
                      "media_id":"'.$content.'"
                   },
                    "msgtype":"mpnews",
                    "send_ignore_reprint":'.$send_ignore_reprint.'';
                break;
            case 'text' :
                $temp = '{
                           "text":{
                              "content":"'.$content.'"
                           },
                            "msgtype":"text"
                        }';
                break;
            case 'voice' :
                $temp = '{
                           "voice":{
                              "media_id":"'.$content.'"
                           },
                            "msgtype":"voice"
                        }';
                break;
            case 'image' :
                $temp = '{
                           "image":{
                              "media_id":"'.$content.'"
                           },
                            "msgtype":"image"
                        }';
                break;
            case 'video' :
                $res = $this->uploadVideo($content);
                $temp = '{
                           "mpvideo":{
                              "media_id":"'.$res['media_id'].'"
                           },
                            "msgtype":"mpvideo"
                        }';
                break;
        }
        $filter = [
            'touser' => $openid_list
        ];
        $data = '{
                   '.json_encode($filter).',
                   '.$temp.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }


    //群发消息所需视频上传
    private function uploadVideo($media_id,$title = '',$description = ''){
        $url = 'https://file.api.weixin.qq.com/cgi-bin/media/uploadvideo?access_token='.$this->getAccessToken();
        $data = '{
                  "media_id": "'.$media_id.'",
                  "title": "'.$title.'",
                  "description": "'.$description.'"
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
}