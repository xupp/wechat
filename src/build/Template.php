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

class Template extends Wx{
    public function getAllPrivateTemplate()
    {
        $url = $this->apiUrl.'/cgi-bin/template/get_all_private_template?access_token='.$this->getAccessToken();
        $result = $this->curl($url);
        return $this->get($result);
    }

    public function sendTemplateMessage($template_id, $content, $openid)
    {
        if (!$template_id){
            return '';
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->getAccessToken();
        $data = array(
            'touser' => $openid,
            'template_id' => $template_id
        );
        if($content['url']){
            $data['url'] = $content['url'];
        }
        if($content['useMini'] && $content['appid']){
            $data['miniprogram'] =  array(
                'appid' => $content['appid'],
                'pagepath' => $content['pagepath'] || ''
            );
        }
        if(is_array($content['data'])){
            $data['data'] = $content['data'];
        }
        $json_template = json_encode($data);
        $result = $this->curl($url, $json_template);
        return $this->get($result);
    }

    public function setTemplateMessageByMini($template_id, $content, $openid)
    {
        if (!$template_id){
            return '';
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->getAccessToken();
        $data = array(
            'touser' => $openid,
            'template_id' => $template_id,
            'form_id' => '',
            'emphasis_keyword' => 'keyword1.DATA'
        );
        if(is_array($content['data'])){
            $data['data'] = $content['data'];
        }
        $json_template = json_encode($data);
        $result = $this->curl($url, $json_template);
        return $this->get($result);
    }
}