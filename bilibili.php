<?php
/**
 * 作者:苏晓晴
 * 作者QQ:3074193836
 * 免费脚本 请勿收费~
 * 个人博客 www.toubiec.cn
 * 感谢墨渊重构的代码！
 */
header('Content-type: text/json;charset=utf-8');
//$urls = 'https://b23.tv/3ygbgeA';
$urls = $_GET['url'];
$array = parse_url($urls);
if (empty($array)) {
    exit(json_encode(['code'=>-1, 'msg'=>"视频链接不正确"], 480));
}elseif ($array['host'] == 'b23.tv') {
    $header = get_headers($urls,true);
    $array = parse_url($header['Location']);
    $bvid = $array['path'];
}elseif ($array['host'] == 'www.bilibili.com') {
    $bvid = $array['path'];
}elseif ($array['host'] == 'm.bilibili.com') {
    $bvid = $array['path'];
}else{
    exit(json_encode(['code'=>-1, 'msg'=>"视频链接好像不太对！"], 480));
}
if (strpos($bvid, '/video/') === false) {
    exit(json_encode(['code'=>-1, 'msg'=>"好像不是视频链接"], 480));
}
$bvid = str_replace("/video/", "", $bvid);
//这里填写你的B站cookie(不填解析不到1080P以上) 格式为_uuid=XXXXX
$cookie = '';
$header = ['Content-type: application/json;charset=UTF-8'];
$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36';
//获取解析需要的cid值和图片以及标题
$json1 = bilibili(
    'https://api.bilibili.com/x/web-interface/view?bvid='.$bvid
    , $header
    , $useragent
    , $cookie
);
$array = json_decode($json1,true);
if($array['code'] == '0'){
    //循环获取
    foreach($array['data']['pages'] as $keys =>$pron){
        //对接上面获取cid值API来取得视频的直链
        $json2 = bilibili(
            "https://api.bilibili.com/x/player/playurl?otype=json&fnver=0&fnval=3&player=3&qn=64&bvid=".$bvid."&cid=".$pron['cid']."&platform=html5&high_quality=1"
            , $header
            , $useragent
            , $cookie
        );
        $array_2 = json_decode($json2,true);
        $bilijson[] = [
            'title' =>  $pron['part']
            ,'duration' => $pron['duration']
            ,'durationFormat' => gmdate('H:i:s', $pron['duration']-1)
            ,'accept' => $array_2['data']['accept_description']
            ,'video_url' =>  $array_2['data']['durl'][0]['url']
        ];
    }
    $JSON = array(
        'code' => 1
        ,'msg' => '解析成功！'
        ,'title' => $array['data']['title']
        ,'imgurl' => $array['data']['pic']
        ,'desc' => $array['data']['desc']
        ,'data' => $bilijson
        ,'user' => [
            'name' => $array['data']['owner']['name']
            , 'user_img' => $array['data']['owner']['face']
        ]
        ,'text' => [
            'msg' => '此接口只支持B站视频 包括番剧 电影 电视剧 都不能解析'
            ,'copyright' => '接口编写:苏晓晴 2022.5.8 基于源代码重构'
        ]
    );
}else{
    $JSON = ['code'=>0, 'msg'=>"解析失败！"];
}
exit(json_encode($JSON,480));
function bilibili($url, $header, $user_agent, $cookie) {
    $ch = curl_init() ;
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
    $output = curl_exec($ch) ;
    curl_close ($ch);
    return $output;
}
?>