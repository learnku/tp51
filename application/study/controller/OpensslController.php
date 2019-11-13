<?php
/**
 * 公、私钥加解密
 */

namespace app\study\controller;

use app\common\utils\Rsa;

class OpensslController extends BashController
{
    private $rsa = null;

    public function index()
    {
        // $this->rsa = new Rsa();
        $this->test();
        $this->test2();
    }

    private function test()
    {
        $this->rsa = new Rsa();
        echo '演示：公钥加密 --> 私钥解密' . '<br>';

        $b1 = $this->rsa->publicEncrypt('我是公钥加密的内容 -- 1');
        echo '公钥加密:<br>' . $b1 . '<br>';

        $b2 = $this->rsa->privateDecrypt($b1);
        echo '私钥解密:' . $b2 . '<br>';
    }

    private function test2()
    {
        $this->rsa = new Rsa();
        echo '<hr>';;
        echo '演示：私钥加密 --> 公钥解密' . '<br>';

        $b3 = $this->rsa->privateEncrypt('我是私钥加密的内容 -- 2');
        echo '私钥加密:<br>' . $b3 . '<br>';

        $b4 = $this->rsa->publicDecrypt($b3);
        echo '公钥解密:' . $b4 . '<br>';
    }
}