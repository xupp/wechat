<?php
/**
 * Author: xupp
 * Date: 2016/12/26
 * Time: 15:43
 */

namespace wechat\build;

use wechat\Wx;

class Menu extends Wx{
    /**
     * 创建自定义菜单
     * @param $data //菜单数据
     * @return array|bool
     */
    public function create($data){
        $url = $this->apiUrl.'/cgi-bin/menu/create?access_token='.$this->getAccessToken();
        $result = $this->curl($url,$data);
        return $this->get($result);
    }

    /**
     * 查询自定义菜单
     * @return array|bool
     */
    public function getMenu()
    {
        $url = $this->apiUrl.'/cgi-bin/menu/get?access_token='.$this->getAccessToken();
        $result = $this->curl($url);
        return $this->get($result);
    }

    /**
     * 删除自定义菜单
     * @return array|bool
     */
    public function flush(){
        $url = $this->apiUrl.'/cgi-bin/menu/delete?access_token='.$this->getAccessToken();
        $result = $this->curl($url);
        return $this->get($result);
    }
}