##
使用方法

第一步、安装&nbsp;<br>
composer require xiaosongshu/appmessage<br>
第二步、引入message类<br>
use Xiaosongshu\Appmessage\Message;<br>
第三步，传入参数并实例化<br>
$client=new Meessage(string $appId,string $appkey,string $mastersecret,string $baseUrl,string $packagename)<br>
第四步、共有三个方法<br>
单个推送： $client->send_one(array $param)<br>
批量推送： $client->send_list(array $param)<br>
全部推送： $client->send_all(array $param)<br>
第五步、推送完成后返回数据<br>
return ['code' => 200, 'msg' => """, 'data' =>[];
<br>
##
开发者联系方式：
<br>
2723659854@qq.com
<br>
171892716@qq.com
