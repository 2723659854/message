## 消息推送

### 项目描述
本项目用于使用个推平台将消息推送到安卓Android和苹果iOS平台。
###  安装 install
```bash
composer require xiaosongshu/appmessage
```
###  demo 示例
```php
<?php
require_once __DIR__.'/vendor/autoload.php';

use Xiaosongshu\Appmessage\Message;
/** 你的APPid */
$appId = '1';
/** 你的APPkey */
$appkey = '2';
/** secret */
$mastersecret = '3';
/** 个推baseurl */
$baseUrl = 'https://restapi.getui.com/v2/1';
/** 包名称 */
$packagename  = '4';
/** 实例化客户端 */
$client= new Message( $appId, $appkey, $mastersecret, $baseUrl, $packagename);
/** 单个推送 cid请用真实的cid，此处仅做示例演示 */
$res = $client->send_one(['title'=>'demo','content'=>'内容','url'=>'跳转地址','cid'=>1]);
/** 批量推送 cid请用真实的cid，此处仅做示例演示 */
$res = $client->send_list(['title'=>'demo','content'=>'内容','url'=>'跳转地址','cids'=>[1,2,3]]);
/** 全部推送 */
$res=$client->send_all(['title'=>'demo','content'=>'','url'=>'']);
```
### 返回码
```php
/** 成功 */
$res = ['code' => 200, 'msg' => "", 'data' =>[]];
/** 失败 */
$res = ['code' => 400, 'msg' => "", 'data' =>[]];
```
### 开发者联系方式：
邮箱： 2723659854@qq.com ，171892716@qq.com

### 其他
最近看到很多朋友在用这个扩展，以前说明文件写的很潦草，现在优化了以下说明文件。
