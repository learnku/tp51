<?php
/**
 * 使用 password_hash   来哈希密码
 * 使用 password_verify 来验证哈希密码
 */

namespace app\study\controller;

use function Sodium\crypto_pwhash_scryptsalsa208sha256;

class PasswordController
{
    protected $rounds = 10;

    public function index()
    {
        echo <<<pre
<h2>即使被拖库，也可以保证密码不泄露</h2>
<ol>
    <li>`md5(md5(password) + salt)`</li>
    <li>`SHA512(SHA512(password) + salt)`</li>
    <li>`bcrypt(SHA512(password), salt, cost)`</li>
</ol>
<h2>本例使用</h2>
<ol>
    <li>`valueHash = password_hash(value, PASSWORD_BCRYPT);`</li>
    <li>`result = password_verify(value, valueHash);`</li>
</ol>
pre;
        echo '<hr>';

        $password = htmlspecialchars('guanjie');

        echo '使用  `password_hash()` 函数加密密码 `liguanjie`' . '<br>';
        $hashPassword = $this->make($password);
        echo "加密后存入数据库：";
        echo $hashPassword;

        echo '<br>';
        echo '<br>';
        echo '使用  `password_verify()` 函数验证密码' . '<br>';

        echo '<br>';
        if ($this->check($password, $hashPassword)){
            return '登录成功';
        }
        return '密码错误';
    }

    /**
     * 生成密码
     * @param $password
     * @param array $option
     * @return bool|string
     */
    protected function make($password, array $option = [])
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, [
            'cost' => $this->cost($option),
        ]);
        return $hash;
    }

    /**
     * 验证密码
     * @param $value
     * @param $hashedValue
     * @param array $options
     * @return bool
     */
    protected function check($value, $hashedValue, array $options = [])
    {
        if (0 === strlen($hashedValue)) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }

    /**
     * 从 $options 中提取 cost 值
     * @param array $options
     * @return int|mixed
     */
    private function cost(array $options = [])
    {
        return $options['rounds'] ?? $this->rounds;
    }
}