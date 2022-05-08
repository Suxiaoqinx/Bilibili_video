<?php
/**
 * ����:������
 * ����QQ:3074193836
 * ��ѽű� �����շ�~
 * ���˲��� www.toubiec.cn
 * ��лīԨ�ع��Ĵ��룡
 */
header('Content-type: text/json;charset=utf-8');
$urls = 'https://b23.tv/3ygbgeA';
$urls = $_GET['url'];
$array = parse_url($urls);
if (empty($array)) {
    exit(json_encode(['code'=>-1, 'msg'=>"��Ƶ���Ӳ���ȷ"], 480));
}elseif ($array['host'] == 'b23.tv') {
    $header = get_headers($urls,true);
    $array = parse_url($header['Location']);
    $bvid = $array['path'];
}elseif ($array['host'] == 'www.bilibili.com') {
    $bvid = $array['path'];
}elseif ($array['host'] == 'm.bilibili.com') {
    $bvid = $array['path'];
}else{
    exit(json_encode(['code'=>-1, 'msg'=>"��Ƶ���Ӻ���̫�ԣ�"], 480));
}
if (strpos($bvid, '/video/') === false) {
    exit(json_encode(['code'=>-1, 'msg'=>"��������Ƶ����"], 480));
}
$bvid = str_replace("/video/", "", $bvid);
//������д���Bվcookie(�����������1080P����) ��ʽΪ_uuid=XXXXX
$cookie = '_uuid=XXXX';
$header = ['Content-type: application/json;charset=UTF-8'];
$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36';
//��ȡ������Ҫ��cidֵ��ͼƬ�Լ�����
$json1 = bilibili(
    'https://api.bilibili.com/x/web-interface/view?bvid='.$bvid
    , $header
    , $useragent
    , $cookie
);
$array      =   json_decode($json1,true);
if($array['code'] == '0'){
    //ѭ����ȡ
    foreach($array['data']['pages'] as $keys =>$pron){
        //�Խ������ȡcidֵAPI��ȡ����Ƶ��ֱ��
        $json2 = bilibili(
            "https://api.bilibili.com/x/player/playurl?otype=json&fnver=0&fnval=3&player=3&qn=64&bvid=".$bvid."&cid=".$pron['cid']."&platform=html5&high_quality=1"
            , $header
            , $useragent
            , $cookie
        );
        $array_2     =   json_decode($json2,true);
        $bilijson[]    =   [
            'title'         =>  $pron['part']
            ,'video_url'    =>  $array_2['data']['durl'][0]['url']
        ];
    }
    $JSON = array(
        'msg' => '�����ɹ���'
        ,'title' => $array['data']['title']
        ,'imgurl' => $array['data']['pic']
        ,'desc' => $array['data']['desc']
        ,'data' => $bilijson
        ,'user' => [
            'name' => $array['data']['owner']['name']
            , 'user_img' => $array['data']['owner']['face']
        ]
        ,'text' => [
            'msg' => '�˽ӿ�ֻ֧��Bվ��Ƶ �������� ��Ӱ ���Ӿ� �����ܽ���'
            ,'copyright' => '�ӿڱ�д:������ 2022.5.4'
        ]
    );
}else{
    $JSON = ['code'=>-1, 'msg'=>"����ʧ�ܣ�"];
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