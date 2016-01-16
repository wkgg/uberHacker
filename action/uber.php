<?php

require_once(  APP_DIR . '/common/lib.fun.php');


/**
 * @file: uber.php
 * @author: ouyang.jianhua
 * @date: 16/1/16
 */

class Uber {

    private $code;
    private $appid;
    private $appsecret;
    private $scope;

    public function __construct( $appid,$appsecret,$scope ){

        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->response_type = 'code';
        $this->scope = $scope;

    }


    /**
     * jsapi配置
     * @return array
     */
    public function oauth(){

        $code = $this->code = get('code');
        $redirect_uri = get('redirect_uri','');
        $redirect_uri = urldecode($redirect_uri);

        $query = array(
            'client_secret' => $this->appsecret,
            'client_id' => $this->appid,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirect_uri,
            'code' => $code,
        );

        $token_info_josn = http_post('https://login.uber.com.cn/oauth/v2/token' ,$query );

        echo $token_info_josn;

        $token_info = json_decode($token_info_josn,true);

        var_dump( $token_info );exit;

    }

    public function gotoOAuth(){

        $query = array(
            'response_type' => $this->response_type,
            'client_id' => $this->appid,
            'scope' => $this->scope,
            'state' => 'authorization'
        );

        $query_string = http_build_query( $query );

        $url = 'https://login.uber.com.cn/oauth/v2/authorize?' . $query_string;

        redirect($url);

    }

} 