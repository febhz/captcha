<?php 

namespace imyfone;

use captcha\Captcha;
use think\facade\Cache;

/**
 *
 * 前后端分离图片验证码 
 */
class TheCaptcha extends Captcha
{
	private $uniqid;
	private $expire=60;
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * 输出验证码并把验证码的值保存的缓存中
     * @access public
     * @param string $id 要生成验证码的标识
     */
	public function getEntry($id = '')
	{
        $response = $this->entry($id);
        $this->writeCache($id);
        $result = [
        	'uniqid' => $this->uniqid,
        	'content' => 'data:image/jpg/png;base64,'.base64_encode($response->getContent()),
        ];
        return json($result);
	}

    /* 加密验证码 */
    private function authcode($str)
    {
        $key = substr(md5($this->seKey), 5, 8);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }

    // 获取原验证码并写入缓存
	public function writeCache($id = '')
	{
        $key = $this->authcode($this->seKey) . $id;
        // 验证码不能为空
        $secode = Session::get($key, '');
        if (empty($secode) || empty($secode['verify_code'])) {
            return false;
        }
        $this->uniqid = md5($secode['verify_code']);
        // 写入缓存
        Cache::set($this->uniqid, $secode['verify_code'], $this->expire);
        return $secode['verify_code'];
	}

    /**
     * 验证验证码是否正确
     * @access public
     * @param string $uniqid 二维码标识
     * @param string $code 用户验证码
     * @param string $id   验证码标识
     * @return bool 用户验证码是否正确
     */
    public function checkCaptcha($uniqid, $code, $id = '')
    {
    	// 获取并删除缓存
        $verify_code = Cache::pull($uniqid);
        if(empty($verify_code))
        {
        	return false;
        }
        $key = $this->authcode($this->seKey) . $id;
        if ($this->authcode(strtoupper($code)) == $verify_code) {
            $this->reset && Session::delete($key, '');
            return true;
        }

        return false;
    }
}
