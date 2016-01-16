<?php

    /*****************
     * 工具类
     *****************/

    /////////////////////////////////////////////////////////////////////

    /**
     * 模拟post请求
     * @param      $url
     * @param      $postdata
     * @param array $header
     * @param bool $ssl
     * @return mixed
     */
    function http_post($url, $postdata, $header=array(),$ssl = false) {
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ci, CURLOPT_TIMEOUT, 20);
        if ($ssl) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ci, CURLOPT_POST, 1);
        curl_setopt($ci, CURLOPT_POSTFIELDS, $postdata);
        if( !empty($header) ){
            curl_setopt ( $ci, CURLOPT_HTTPHEADER, $header);
        }
        $response = curl_exec($ci);
        if (false === $response) {
            trigger_error(sprintf("url[%s] error[%s]", $url, curl_error($ci)));
        }
        curl_close($ci);

        return $response;
    }

    /**
     * 模拟get请求
     * @param       $url
     * @param array $header
     * @return mixed
     */
    function http_get($url,$header=array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        if( !empty($header) ){
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header);
        }

        $response = curl_exec($ch);
        if (false === $response) {
            trigger_error(sprintf("url[%s] error[%s]", $url, curl_error($ch)));
        }
        curl_close($ch);

        return $response;
    }

    function post($name, $default = null) {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    function get($name, $default = null) {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    function redirect($url, $end=true) {
        echo $url;exit;
        ob_start();
        ob_clean();
        header('Location: ' . $url);
        ob_end_flush();
        if($end) {
            exit();
        }
    }