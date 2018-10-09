<?php

/**
 * Description of xxtea
 *
 * @author Marco
 */
class xxtea_lib
{
    //put your code here
    public $password;
    public static $string;
    const PIVATEKEY = 'Qghq&)hfw*$&UHI___';

    const PASSWORD_COST = 11; // 这里配置bcrypt算法的代价，根据需要来随时升级
    const PASSWORD_ALGO = PASSWORD_BCRYPT; // 默认使用（现在也只能用）bcrypt

    public static function encode($value, $base64 = true)
    {
        return xxTea::encrypt($value, self::PIVATEKEY, $base64);
    }

    public static function decode($value, $base64 = true)
    {
        return xxTea::decrypt($value, self::PIVATEKEY, $base64);
    }

    public function verifyPassword($plainPassword, $autoRehash = true)
    {
        if (password_verify($plainPassword, $this->password)) {
            if ($autoRehash && password_needs_rehash(
                    $this->password, self::PASSWORD_ALGO,
                    ['cost' => self::PASSWORD_COST])) {
                return $this->updatePassword($plainPassword);
                // updata sql
            }

            return true;
        }
        return false;
    }

    /**
     * 更新密码
     *
     * @param string $newPlainPassword
     */
    public function updatePassword($newPlainPassword)
    {
        $password = password_hash(
            $newPlainPassword, self::PASSWORD_ALGO,
            ['cost' => self::PASSWORD_COST]);
        return $password;
    }

    public static function Encrypt($string)
    {
        $password = password_hash(
            $string, self::PASSWORD_ALGO,
            ['cost' => self::PASSWORD_COST]);
        return $password;
    }

    public static function Verify($plainPassword, $hash_string= null, $autoRehash = true)
    {
            if (password_verify($plainPassword, $hash_string)) {
            if ($autoRehash && password_needs_rehash(
                    $hash_string, self::PASSWORD_ALGO,
                    ['cost' => self::PASSWORD_COST])) {
                return self::updatePassword($plainPassword);
            }
            return true;
        }
        return false;
    }
}