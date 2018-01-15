<?php
/**
 * Author: xupp
 * Date: 2016/12/26
 * Time: 14:38
 */

namespace wechat\build;

use wechat\Wx;

class Tag extends Wx{
    //创建标签
    public function createTag($name){
        $name = strlen($name) > 30 ? mb_substr($name,0,30) : $name;
        $url = $this->apiUrl.'/cgi-bin/tags/create?access_token='.$this->getAccessToken();
        $data = '{
                  "tag" : {
                    "name" : "'.$name.'"
                  }
                 }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //获取公众号已创建的标签
    public function getTag(){
        $url = $this->apiUrl.'/cgi-bin/tags/get?access_token='.$this->getAccessToken();
        $result = $this->curl($url);
        return $this->get($result);
    }
    //编辑标签
    public function updateGroup($tag_id,$name){
        $name = strlen($name) > 30 ? mb_substr($name,0,30) : $name;
        $url = $this->apiUrl.'/cgi-bin/tags/update?access_token='.$this->getAccessToken();
        $data = '{
                    "tag":{
                        "id":'.$tag_id.',
                        "name":"'.$name.'"
                    }
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //获取标签下粉丝列表
    public function getTagUser($tag_id,$next_openid = '')
    {
        $url = $this->apiUrl.'/cgi-bin/user/tag/get?access_token='.$this->getAccessToken();
        $data = '{
                  "tagid" : '.$tag_id.',
                  "next_openid":"'.$next_openid.'"
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //删除标签
    public function deleteGroup($tag_id){
        $url = $this->apiUrl.'/cgi-bin/tags/delete?access_token='.$this->getAccessToken();
        $data = '{
                    "tag":{
                        "id":'.$tag_id.'
                    }
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //批量为用户打标签
    public function batchTagging(array $openid_list,$tag_id)
    {
        $url = $this->apiUrl.'/cgi-bin/tags/members/batchtagging?access_token='.$this->getAccessToken();
        $data = [
            'openid_list' => $openid_list,
            'tagid' => $tag_id
        ];
        $result = $this->curl($url,json_encode($data));
        return $this->get($result);
    }
    //批量为用户取消标签
    public function batchUnTagging(array $openid_list,$tag_id)
    {
        $url = $this->apiUrl.'/cgi-bin/tags/members/batchuntagging?access_token='.$this->getAccessToken();
        $data = '{
                  "openid_list" : ['.$openid_list.'],
                  "tagid" : '.$tag_id.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //获取用户身上的标签列表
    public function getIdList($openid)
    {
        $url = $this->apiUrl.'/cgi-bin/tags/getidlist?access_token='.$this->getAccessToken();
        $data = '{
                  "openid" : "'.$openid.'"
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
}