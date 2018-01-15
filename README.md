# wechat
微信SDK

/*======================使用方法===========================*/

require "./vendor/autoload.php";

use wechat\Wx;

$config = [
	          'token' => 'weixin',
	          'appId' => 'wx917fc314c6f429ff',
	          'appSecret' => 'e7d5c0c720bb9cbd7248bf602b3f17b3'
	      ];
$wx = new Wx($config);

$wx->valid();

$message = $wx->instance('message');

if($message->isTextMsg()){
	$message->text('我的微信sdk发布了');
}