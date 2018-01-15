# wechat
微信SDK
<<<<<<< HEAD

/*======================使用方法===========================*/

=======
<pre><code>
/*======================使用方法===========================*/

##composer require xupp/wechat

>>>>>>> origin/master
require "./vendor/autoload.php";

use wechat\Wx;

$config = [
<<<<<<< HEAD
	        'token' => '您的token',
	        'appId' => '您的appId',
            'appSecret' => '您的appSecret'
	      ];
=======
	          'token' => '您的token',
	          'appId' => '您的appId',
	          'appSecret' => '您的appSecret'
	      ];
	      
>>>>>>> origin/master
$wx = new Wx($config);

$wx->valid();

$message = $wx->instance('message');

if($message->isTextMsg()){
	$message->text('我的微信sdk发布了');
<<<<<<< HEAD
}
=======
}

</code></pre>

>>>>>>> origin/master
