<?php
/**
 * Author: xupp
 * Date: 2016/12/26
 * Time: 14:38
 */

namespace wechat\build;

use wechat\Wx;

class User extends Wx{
    //获取用户基本信息
    public function getUserInfo($openid)
    {
        $url = $this->apiUrl.'/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $data = $this->curl($url);
        return $this->get($data);
    }
    //获取用户列表
    public function getUserLists($next_openid = '')
    {
        $url = $this->apiUrl.'/cgi-bin/user/get?access_token='.$this->getAccessToken().'&next_openid='.$next_openid;
        $data = $this->curl($url);
        return $this->get($data);
    }
    //批量获取用户信息
    public function getUserInfoLists(array $data,$lang = 'zh-CN')
    {
        $url = $this->apiUrl.'/cgi-bin/user/info/batchget?access_token='.$this->getAccessToken();
        $user_list = [];
        foreach((array) $data as $openid){
            $user_list['user_list'][] = ['openid'=>$openid,'lang'=>zh-CN];
        }
        $data = $this->curl($url,json_encode($user_list,JSON_UNESCAPED_UNICODE));
        return $this->get($data);
    }
    //获取公众号的黑名单列表
    public function getBlackList($openid = ''){
        $url = $this->apiUrl.'/cgi-bin/tags/members/getblacklist?access_token='.$this->getAccessToken();
        $data = '{
                    "begin_openid":"'.$openid.'"
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //拉黑用户
    public function batchBlackList(array $openid_list){
        $url = $this->apiUrl.'/cgi-bin/tags/members/batchblacklist?access_token='.$this->getAccessToken();
        $data = '{
                    "opened_list":'.$openid_list.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
    //取消拉黑用户
    public function batchUnBlackList(array $openid_list){
        $url = $this->apiUrl.'/cgi-bin/tags/members/batchunblacklist?access_token='.$this->getAccessToken();
        $data = '{
                    "opened_list":'.$openid_list.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
}