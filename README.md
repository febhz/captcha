## 环境需求

- PHP >= 5.6

## 介绍

前后端分离使用的验证码

## 安装

```shell
composer require imyfone-tp/captcha
```

## 使用
发送验证码
```php
//
use imyfone\TheCaptcha;
//自定义配置，可以用默认的不配置
$config=[
	'codeSet'  => '123456',
    // 验证码字符集合
    'expire'   => 1800,
    // 使用背景图片
    'fontSize' => 25,
    // 中文验证码字符串
    'useImgBg' => false,

    'length'   => 4,
    // 验证码位数
    'fontttf'  => '',
    // 验证码字体，不设置随机获取
];
$id = 'hello';
$captcha = new TheCaptcha($config);
return $captcha->getEntry($id);
```

```php
//验证操作
use imyfone\TheCaptcha;
$uniqid = $_POST['uniqid'];
$code = $_POST['code'];
$captcha = new TheCaptcha();
if($captcha->checkCaptcha($uniqid,$code) === true){
	//验证成功
}

```
