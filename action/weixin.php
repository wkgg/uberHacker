<?php

require_once(  APP_DIR . '/common/lib.fun.php');


/**
 * @file: weixin.php
 * @author: ouyang.jianhua
 * @date: 16/1/16
 */

class Weixin {

    public function __construct( $appid,$appsecret ){

        $this->appid = $appid;
        $this->appsecret = $appsecret;

    }


    /**
     * jsapi配置
     * @return array
     */
    public function getJsApiConf(){
        $token = $this->getToken();
        var_dump($token);
        $access_token =  $token['access_token'];
        $jsapi_ticket = $this->getJsApiTicket($access_token);
        $jsapi_ticket = $jsapi_ticket['ticket'];
        $jsapi_list = array('hideOptionMenu','showMenuItems','onMenuShareTimeline','onMenuShareAppMessage', 'getLocation', 'openLocation');
        $jsaip_conf = $this->getJsApiConfParams($jsapi_ticket,$jsapi_list,false);
        return $jsaip_conf;
    }

    private function getToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $token = http_get($url);
        return json_decode($token,true);
    }

    private function getJsApiTicket($access_token){
        /**
         成功返回如下JSON：
            {
                "errcode":0,
                "errmsg":"ok",
                "ticket":"bxLdikRXVbTPdHSM05e5u5sUoXNKd8-41ZO3MhKoyN5OfkWITDGgnr2fwJ0m9E8NYzWKVZvdVtaUgWvsdshFKA",
                "expires_in":7200
            }
         */
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
        $jsapi_ticket = http_get($url);
        return json_decode($jsapi_ticket,true);
    }

    /**
     * 生成jsapi配置
     * @param       $jsapi_ticket
     * @param array $js_api_list
     * @param bool  $debug
     *
     * @return array
     */
    private function getJsApiConfParams($jsapi_ticket, $js_api_list,$debug=false){

        /**
            wx.config({
                debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: '', // 必填，公众号的唯一标识
                timestamp: , // 必填，生成签名的时间戳
                nonceStr: '', // 必填，生成签名的随机串
                signature: '',// 必填，签名，见附录1
                jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            });
         */

        $host = 'http://' . $_SERVER['HTTP_HOST'];

        $url = rtrim($host,'/');
        if( $_SERVER['REQUEST_URI'] ){
            $url .= $_SERVER['REQUEST_URI'];
        }
        $nonceStr = strtoupper($this->generateCode(rand(24,30)));
        $timestamp = time();
        //$rand_id = strtoupper(md5(time() . mt_rand()));
        //$nonceStr =  strtoupper( substr($rand_id, rand(0, 27), 24) );
        $sign_params = array(
            'noncestr' => $nonceStr,
            'jsapi_ticket' => $jsapi_ticket,
            'timestamp' => $timestamp,
            'url' => $url
        );
        ksort($sign_params);
        $signature = sha1( urldecode(http_build_query($sign_params)) );
        $conf =  array(
            'appId' => $this->appid,
            'timestamp' => $timestamp,
            'nonceStr' => $nonceStr,
            'signature' => $signature,
            'jsApiList' => $js_api_list,
        );
        if( $debug ){
            $conf['debug'] = true;
        }

        return $conf;
    }

    private function generateCode( $len = 6, $pre = '' ) {
        $code = strtoupper(md5(time() . mt_rand() . md5(time().'oyjh') ));
        return strtoupper( $pre . substr($code, rand(0,5), $len) );
    }

} 