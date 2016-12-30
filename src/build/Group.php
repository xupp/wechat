<?php
/**
 * Author: xupp
 * Date: 2016/12/26
 * Time: 14:38
 */

namespace wechat\build;

use wechat\Wx;

class Group extends Wx{
    //创建分组
    public function createGroup($name){
        $name = strlen($name) > 30 ? mb_substr($name,0,30) : $name;
        $url = $this->apiUrl.'/cgi-bin/groups/create?access_token='.$this->getAccessToken();
        $data = '{
                    "group":{
                        "name":"'.$name.'"
                    }
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //查询所有分组
    public function getGroup(){
        $url = $this->apiUrl.'/cgi-bin/groups/get?access_token='.$this->getAccessToken();
        $result = $this->curl($url);
        return $this->get($result);
    }
    //查询用户所在分组
    public function getOpenidGroup($openid){
        $url = $this->apiUrl.'/cgi-bin/groups/getid?access_token='.$this->getAccessToken();
        $data = '{
                    "openid":"'.$openid.'"
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //修改分组名
    public function updateGroup($group_id,$name){
        $name = strlen($name) > 30 ? mb_substr($name,0,30) : $name;
        $url = $this->apiUrl.'/cgi-bin/groups/update?access_token='.$this->getAccessToken();
        $data = '{
                    "group":{
                        "id":'.$group_id.',
                        "name":"'.$name.'"
                    }
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //移动用户分组
    public function moveOpenidGroup($openid,$group_id){
        $url = $this->apiUrl.'/cgi-bin/groups/members/update?access_token='.$this->getAccessToken();
        $data = '{
                    "openid":"'.$openid.'",
                    "to_groupid":'.$group_id.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //批量移动用户分组
    public function moveBatchOpenidGroup(array $openid_list,$group_id){
        $url = $this->apiUrl.'/cgi-bin/groups/members/batchupdate?access_token='.$this->getAccessToken();
        $data = [
            'openid_list' => $openid_list,
            'to_groupid' => $group_id
        ];
        $result = $this->curl($url,json_encode($data));
        return $this->get($result);
    }
    //删除分组
    public function deleteGroup($group_id){
        $url = $this->apiUrl.'/cgi-bin/groups/delete?access_token='.$this->getAccessToken();
        $data = '{
                    "group":{
                        "id":'.$group_id.'
                    }
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
}