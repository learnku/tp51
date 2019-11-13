<?php
/**
 * PHP 公私钥加解密
 *      $a = new Rsa();
 *
 *      $b1 = $a->publicEncrypt('李观杰');
 *      echo '公钥加密:<br>' . $b1 . '<br>';
 *      $b2 = $a->privateDecrypt($b1);
 *      echo '私钥解密:' . $b2 . '<br><br><br>';
 *
 *      $b3 = $a->privateEncrypt('关节李');
 *      echo '私钥加密:<br>' . $b3 . '<br>';
 *      $b4 = $a->publicDecrypt($b3);
 *      echo '公钥解密:' . $b4 . '<br>';
 */

namespace app\common\utils;


class Rsa
{
    // 更多配置：https://www.php.net/manual/zh/function.openssl-csr-new.php
    private $config = [
        // 可用算法：https://www.php.net/manual/zh/function.openssl-get-md-methods.php
        "digest_alg" => "sha256",
        // 字节数    512 1024  2048   4096 等
        "private_key_bits" => 1024,
        // 加密类型：默认值
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    private $_config = [
        'public_key' => '',     // 公钥
        'private_key' => '',    // 私钥
    ];

    // 公、私钥资源
    private $res = false;

    /**
     * Rsa constructor.
     * @param $private_key_filepath
     * @param $public_key_filepath
     */
    public function __construct($private_key_filepath = false, $public_key_filepath = false)
    {
        $public_key_filepath = $public_key_filepath ?: ROOT_PATH . '/runtime/rsa_key.pem';
        $private_key_filepath = $private_key_filepath ?: ROOT_PATH . '/runtime/rsa_pub_key.pem';
        $this->_config['public_key'] = $this->getKeyContents($public_key_filepath, 1);
        $this->_config['private_key'] = $this->getKeyContents($private_key_filepath, 2);
    }

    /**
     * 公钥加密
     * @param string $data
     * @return null|string
     */
    public function publicEncrypt($data = '') {
        if (!is_string($data)) {
            return null;
        }
        if (openssl_public_encrypt($data, $crypttext, openssl_get_publickey($this->_config['public_key']))) {
            return(base64_encode($crypttext));
        }
        return null;
    }

    /**
     * 公钥解密
     * @param string $crypttext
     * @return string|null
     */
    public function publicDecrypt($crypttext = '') {
        if (!is_string($crypttext)) {
            return null;
        }
        $crypttext = base64_decode($crypttext);
        if (openssl_public_decrypt($crypttext, $data, openssl_get_publickey($this->_config['public_key']))) {
            return($data);
        }
        return null;
    }

    /**
     * 私钥加密
     * @param string $data
     * @return null|string
     */
    public function privateEncrypt($data = '') {
        if (!is_string($data)) {
            return null;
        }
        if (openssl_private_encrypt($data, $crypttext, openssl_get_privatekey($this->_config['private_key']))) {
            return(base64_encode($crypttext));
        }
        return null;
    }

    /**
     * 私钥解密
     * @param string $crypttext
     * @return null
     */
    public function privateDecrypt($crypttext = '')
    {
        if (!is_string($crypttext)) {
            return null;
        }
        $crypttext = base64_decode($crypttext);
        if (openssl_private_decrypt($crypttext, $data, openssl_get_privatekey($this->_config['private_key']))) {
            return($data);
        }
        return null;
    }

    /**
     * 获取公、私钥内容
     * @param string $file_path 存放路径
     * @param int $mod 1:公钥、2:私钥
     * @return false|string
     */
    private function getKeyContents($file_path, $mod = 1)
    {
        if (!file_exists($file_path)) {
            // 如果不存在，则去生成
            if (1 == $mod) {
                $this->build_public_key($file_path);
            } else {
                $this->build_private_key($file_path);
            }
        }
        return file_get_contents($file_path);
    }

    /**
     * 生成公、私钥资源
     * https://www.php.net/manual/zh/function.openssl-pkey-new.php
     * @return bool
     */
    private function build_res()
    {
        if (!$this->res) {
            $this->res = openssl_pkey_new($this->config);
            if (!$this->res) {
                // 资源生成失败
                return false;
            }
        }
        return true;
    }

    /**
     * 生成公钥
     * @param string $file_path rsa_public_key.pem
     */
    private function build_public_key($file_path)
    {
        if ($this->build_res()) {
            $publicKey = openssl_pkey_get_details($this->res);
            $publicKey = $publicKey["key"];

            // 存储成 .pem 文件
            $fp = fopen($file_path, "w");
            fwrite($fp, $publicKey);
            fclose($fp);
        };
    }

    /**
     * 生成私钥
     * @param string $file_path rsa_private_key.pem
     */
    private function build_private_key($file_path)
    {
        if ($this->build_res()) {
            openssl_pkey_export($this->res, $privateKey, null, $this->config);

            // 存储成 .pem 文件
            $fp = fopen($file_path, "w");
            fwrite($fp, $privateKey);
            fclose($fp);
        }
    }
}