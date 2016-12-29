<?php
/**
 * Author: shizhenyuan
 * Date: 2016/12/26
 * Time: 16:22
 */

namespace wechat\build;

use wechat\Wx;

class Material extends Wx{
    //上传素材
    public function upload($file,$type = 'image',$materialType = 0){
        switch($materialType){
            case 0 :
                //永久
                $url = $this->apiUrl.'/cgi-bin/material/add_material?access_token='.$this->getAccessToken().'&type='.$type;
                break;
            default :
                //临时
                $url = $this->apiUrl.'/cgi-bin/media/upload?access_token='.$this->getAccessToken().'&type='.$type;
                break;
        }
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

    //获取素材
    public function getMaterial($media_id,$materialType = 0){
        switch($materialType){
            case 0 :
                //永久
                $url = $this->apiUrl.'/cgi-bin/material/get_material?access_token='.$this->getAccessToken();
                $data = '{
                            "media_id":'.$media_id.'
                        }';
                $result = $this->curl($url,$data);
                break;
            default :
                //临时
                $url = $this->apiUrl.'/cgi-bin/media/get?access_token='.$this->getAccessToken().'&media_id='.$media_id;
                $result = $this->curl($url);
                break;
        }
        return $this->get($result);
    }

    //删除永久素材
    public function deleteMaterial($media_id){
        $url = $this->apiUrl.'/cgi-bin/material/del_material?access_token='.$this->getAccessToken();
        $data = '{
                    "media_id":'.$media_id.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }

    //获取素材总数
    public function getMaterialCount(){
        $url = $this->apiUrl.'/cgi-bin/material/get_materialcount?access_token='.$this->getAccessToken();
        $result = $this->curl($url);
        return $this->get($result);
    }

    //获取素材列表
    public function batchGetMaterial($type='image',$offset=0,$count=10){
        if($count < 0 || $count > 20){
            return false;
        }
        $url = $this->apiUrl.'/cgi-bin/material/batchget_material?access_token='.$this->getAccessToken();
        $data = '{
                   "type":'.$type.',
                   "offset":'.$offset.',
                   "count":'.$count.'
                }';
        $result = $this->curl($url,$data);
        return $this->get($result);
    }
}