<!--
////////////////////////////////////////////////////////////////////
//                          _ooOoo_                               //
//                         o8888888o                              //
//                         88" . "88                              //
//                         (| ^_^ |)                              //
//                         O\  =  /O                              //
//                      ____/`---'\____                           //
//                    .'  \\|     |//  `.                         //
//                   /  \\|||  :  |||//  \                        //
//                  /  _||||| -:- |||||-  \                       //
//                  |   | \\\  -  /// |   |                       //
//                  | \_|  ''\---/''  |   |                       //
//                  \  .-\__  `-`  ___/-. /                       //
//                ___`. .'  /--.--\  `. . ___                     //
//             ."" '<  `.___\_<|>_/___.'  >'"".                   //
//            | | :  `- \`.;`\ _ /`;.`/ - ` : | |                 //
//            \  \ `-.   \_ __\ /__ _/   .-` /  /                 //
//     ========`-.____`-.___\_____/___.-`____.-'========          //
//                          `=---='                               //
//     ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^          //
//         佛祖保佑          永无Bug          永不宕机               //
//    Name：耗子Linux面板   Author：耗子   Date：2022-10-14          //
////////////////////////////////////////////////////////////////////
-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ config('panel.name') }}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
    <link href="{{asset('layui/css/layui.css')}}" rel="stylesheet">
    <script src="https://cdnjs.cdn.wepublish.cn/ace/1.6.1/ace.js"></script>
</head>
<body>
<div id="HaoZi_panel"></div>
<script src="{{asset('layui/layui.js')}}"></script>
<script>
    layui.config({
        base: 'panel/'
        , version: {{config('panel.version')}}
    }).use('index', function () {
        var layer = layui.layer, admin = layui.admin, $ = layui.jquery;
        layer.ready(function () {
            /**
             * TODO: 因为开发临时注释了测试版tips，需要取消注释
             */
            /*admin.popup({
                content: '当前面板为测试版本，如遇到问题请联系耗子反馈！</br>QQ: 823374000'
                , area: '380px'
                , shade: false
                , offset: 't'
            });*/
        });
    });
</script>
</body>
</html>
