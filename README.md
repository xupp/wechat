# wechat
微信SDK
<pre><code>
/*======================使用方法===========================*/

##composer require xupp/wechat

require "./vendor/autoload.php";

use wechat\Wx;

$config = [
	          'token' => '您的token',
	          'appId' => '您的appId',
	          'appSecret' => '您的appSecret'
	      ];
	      
$wx = new Wx($config);

$wx->valid();

$message = $wx->instance('message');

if($message->isTextMsg()){
	$message->text('我的微信sdk发布了');
}

</code></pre>

