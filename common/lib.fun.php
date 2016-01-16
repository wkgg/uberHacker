<?php

    /*****************
     * 工具类
     *****************/

    /////////////////////////////////////////////////////////////////////

    /**
     * 模拟post请求
     * @param      $url
     * @param      $postdata
     * @param bool $ssl
     * @return mixed
     */
    function http_post($url, $postdata, $ssl = false) {
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
        $response = curl_exec($ci);
        if (false === $response) {
            trigger_error(sprintf("url[%s] error[%s]", $url, curl_error($ci)));
        }
        curl_close($ci);

        return $response;
    }

    /**
     * 模拟get请求
     * @param $url
     * @return mixed
     */
    function http_get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);
        if (false === $response) {
            trigger_error(sprintf("url[%s] error[%s]", $url, curl_error($ch)));
        }
        curl_close($ch);

        return $response;
    }