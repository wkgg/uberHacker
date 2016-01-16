<!DOCTYPE html>
<html>
<head>
    <title>一件呼叫，快捷打车</title>
    <meta charset="utf-8">
    <meta name="viewport" content="target-densitydpi=320,width=640,user-scalable=0,maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style>a,body,div,footer,h1,h2,h3,h4,h5,h6,header,html,img,input,li,p,section,select,span,textarea,ul{margin:0;padding:0;-webkit-tap-highlight-color:rgba(255,0,0,0);border:none;outline:0;-webkit-touch-callout:none}html{min-height:100%}body{min-height:100%;font-family:'\5FAE\8F6F\96C5\9ED1','黑体','Helvetica Neue',Helvetica,Arial,sans-serif;font-size:30px;background:#f5f5eb}a,body{color:#000}.fixed-bottom,.fixed-screen-bottom{position:fixed;width:100%;height:100%;left:0;bottom:0}.clearfix:after,.clearfix:before{display:table;line-height:0;content:""}.clearfix:after{clear:both}a{text-decoration:none}img{max-width:100%}li{list-style:none}.fl{float:left}.fr{float:right}.radius5{border-radius:5px}.radius10{border-radius:10px}.hide{display:none}.show{display:block}.show-in{display:inline-block}.fixed-bottom{-webkit-backface-visibility:hidden}.fixed-bottom.blackbg{background:#292928;background:rgba(41,41,40,.95)}.icon,.icon-del-img{display:inline-block}.icon{background:url(http://cdnst.momocdn.com/static/m4/img/icon.png?20150126) no-repeat;background-size:250px auto;vertical-align:middle}</style>

    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>

    <?php

        define('APP_DIR', dirname(__FILE__));

        require_once( APP_DIR . '/action/weixin.php');
        require_once( APP_DIR . '/action/uber.php');
        $appid = 'wx895ed4a1115b7e54';
        $appsecret = 'd4624c36b6795d1d99dcf0547af5443d';
        $weixin = new Weixin( $appid, $appsecret );

        $uber_appid = 'cmLDMkZ4cr9rivGlPd8Z6AQM-gdwiU2t';
        $uber_appsecret = 'q7ImotFQf1BsgKhsT0STUrq0Vd8IquRSpcoBdS4A';

        $uber = new Uber($uber_appid,$uber_appsecret,'profile');

        if( get('code') && get('state') == 'authorization' ){
            $uber->oauth();
            exit;
        }

        $uber->gotoOAuth();

        $jsSDK = $weixin->getJsApiConf();

    ?>

    <script>

        var UberHackthon = {
            ajax : function( url, data, callback, method, errorCallback ) {
                var xhr = new window.XMLHttpRequest;
                var sdata = data || '' ;
                xhr.onreadystatechange = function(){
                    if ( xhr.readyState == 4 ) {
                        if( xhr.status == 200 ) {
                            typeof callback == 'function' && callback.call( null, xhr.responseText );
                        } else if( xhr.status==0 ) {
                            typeof errorCallback == 'function' && errorCallback.call( null, xhr.responseText );
                        }
                    }
                };
                xhr.open( method, url, true );
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=UTF-8');
                xhr.send( sdata );
            },
            get : function( url, callback, errorCallback ) {
                this.ajax( url, '', callback,"get", errorCallback );
            },
            callLoaction : function(data){
                alert( JSON.stringify(data));
            }
        }

        wx.config( <?php echo json_encode( $jsSDK ); ?> );

        wx.ready(function(){

            wx.onMenuShareTimeline({
                title: '一件呼叫，快捷打车',
                link: 'http://www.baidu.com',
                imgUrl: 'http://static.uberx.net.cn/web-fresh/vehicles/car-x-1000-800@2x.png',
                trigger: function (res) {

                },
                success: function () {
                },
                cancel: function () {
                },
                fail: function (res) {
                }
            });
            wx.onMenuShareAppMessage({
                title: '一件呼叫，快捷打车',
                link: window.location.href,
                imgUrl: 'http://static.uberx.net.cn/web-fresh/vehicles/car-x-1000-800@2x.png',
                desc: '我在微软亚太研发中心，快过来参加Uber hackthon吧，点击一件呼叫就到',
                type: 'link',
                dataUrl: '',
                success: function () {
                },
                cancel: function () {
                }
            });

            wx.getLocation({
                type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                success: function (res) {
                    var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                    var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                    var speed = res.speed; // 速度，以米/每秒计
                    var accuracy = res.accuracy; // 位置精度

                    alert(JSON.stringify(res));

                    document.write('<scr'+'ipt src="http://api.map.qq.com/rgeoc/?lnglat='+longitude +',' + latitude + '&output=jsonp&fr=mapapi&cb=UberHackthon.callLoaction"></sc' + 'ript>');

                }
            });

        });

    </script>
</head>
<body>

<?php
    print_r($jsSDK);
?>

</body>
</html>