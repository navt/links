<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Хелперы приложения сведены в один файл, чтобы избежать дублирования
 */

if ( ! function_exists('deleteSI')) {
    /*
     * deleteSI стирает элемент(ы) массива $_SESSION, если тот(они) существует
     */ 
    function deleteSI($items)
    {
        if (!is_array($items) && $items !== '') {
            $itemsArray[] = $items;
        } else {
            $itemsArray = $items;
        }
        foreach ($itemsArray as $name) {
            if (isset($_SESSION[$name]) && $_SESSION[$name] !='') {
                $_SESSION[$name] = null;
            } 
        }
    }

}

if ( ! function_exists('emailValidate')) {
    /**
     * Email validate
     *
     * @category   validate
     * @version    0.1
     * @license    GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
     * @param string $email проверяемый email
     * @param boolean $dns проверять ли DNS записи
     * @return boolean Результат проверки почтового ящика
     * @author Anton Shevchuk
     */
    function emailValidate($email, $dns = true)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($user, $domain) = explode("@", $email, 2);
            if (!$dns || ($dns && checkdnsrr($domain, "MX") && checkdnsrr($domain, "A"))) {
                $error = false;
            } else {
                $error = 'dns resource record';
            }
        } else {
            $error = 'format';
        }
        return $error;
    }
}

if ( ! function_exists('genHash')) {
    /**
     * genHash генерация хеша из строки и соли по алгоритму md5
     * @param  [string] $userName соль для md5
     * @param  [string] $passWord строка, для которой нужен хеш
     * @return [string]           хеш d5
     */
    function genHash($userName, $passWord)
    {
            // md5
            $hash = crypt($passWord, '$1$'.$userName);
            return $hash;
    }
}
if ( ! function_exists('refresh')) {
    function refresh($uri='',$delay=2) {
        if ( ! preg_match('~^(\w+:)?//~i', $uri)) {
            // site_url заменён на base_url, чтобы убрать из адресной стоки index.php
            $uri = base_url($uri);
        }
        header("Refresh:{$delay};url={$uri}");
        echo "Пауза {$delay} сек.  ...";
        exit;
    }
}