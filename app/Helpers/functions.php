<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/6
 * Time: 13:44
 */

/**
 * 接口返回
 * @param number $id 参数id
 */
if (!function_exists('jsonReturn')) {
    function jsonReturn($code = 200, $msg = '', $data = null)
    {
        $arr = [
            'code' => $code,
            'msg' => $msg,
        ];
        if ($data) $arr['data'] = $data;
        return json_encode($arr);
    }
}

/**
 * 生成订单号接口
 * @return string
 */
if (!function_exists('orderCreate')) {
    function orderCreate()
    {
        $code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        
        $osn = $code[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        
        return $osn;
    }
}

/**
 * [CheckMobile 手机号码格式校验]
 * @param    [int] $mobile [手机号码]
 * @return   [boolean]     [正确true，失败false]
 * @author   Fesion
 * @version  0.0.1
 * @datetime 2019-08-28
 */
if (!function_exists('CheckMobile')) {
    function CheckMobile($mobile)
    {
        return (preg_match('/^1((3|5|8|7){1}\d{1})\d{8}$/', $mobile) == 1) ? true : false;
    }
}

/**
 * [EncryptPassword 密码加密]
 * @param    [string] $pwd  [需要加密的密码]
 * @param    [string] $salt [配合密码加密的随机数]
 * @return   [string]       [加密后的密码]
 */
if (!function_exists('EncryptPassword')) {
    function EncryptPassword($pwd, $salt = '', $encrypt = 'md5')
    {
        return $encrypt(trim($pwd) . $salt);
    }
}
/**
 * 获取全球唯一标识
 * @return string
 */
if (!function_exists('uuid')) {
    
    function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}

/**
 *
 * 获取客户端真实ip
 * @return int
 */
if (!function_exists('getIp')) {
    function getIp()
    {
        $realip = '';
        $unknown = 'unknown';
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } else if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = $unknown;
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)) {
                $realip = getenv("REMOTE_ADDR");
            } else {
                $realip = $unknown;
            }
        }
        $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
        return $realip;
    }
}
/**
 * 请求
 */
if (!function_exists('https_request')) {
    function https_request($url, $curlPost = null, $async = 'GET')
    {
        $headers = array("Content-Type:application/json;charset='utf-8'", "Accept: application/json", "Cache-Control: no-cache", "Pragma: no-cache");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($async == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            curl_setopt($ch, CURLOPT_POST, 1);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}


/**
 * 递归获取子菜单
 * @param $pid
 * @param $menuList
 * @return array
 */
if (!function_exists('buildMenuChild')) {
    function buildMenuChild($pid, $menuList)
    {
        $treeList = [];
        
        foreach ($menuList as $v) {
            if ($pid == $v['pid']) {
                $node = (array)$v;
                $child = buildMenuChild($v['id'], $menuList);
                if (!empty($child)) {
                    $node['children'] = $child;
                }
                // todo 后续此处加上用户的权限判断
                $treeList[] = $node;
            }
        }
        return $treeList;
    }
}

/**
 * 无线分类树
 * @param $list
 * @param int $pid
 * @param int $level
 * @param string $html
 * @return array
 */
if (!function_exists('tree')) {
    function tree(&$list, $pid = 0, $level = 0, $html = '—|')
    {
        static $tree = array();
        foreach ($list as $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $v['html'] = str_repeat($html, $level);
                $tree[] = $v;
                tree($list, $v['id'], $level + 1, $html);
            }
        }
        return $tree;
    }
}

/**
 * 上传图片到本地
 * @param $imgName
 * @return mixed
 */
if (!function_exists('upload')) {
    function upload($imgName, $fileType = 'images')
    {
        $file = request()->file($imgName);
        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();    //客户端文件名称..
            $tmpName = $file->getFileName();   //缓存在tmp文件夹中的文件名例如php8933.tmp 这种类型的.
            $realPath = $file->getRealPath();     //这个表示的是缓存在tmp文件夹下的文件的绝对路径
            $entension = $file->getClientOriginalExtension();   //上传文件的后缀.
            $mimeTye = $file->getMimeType();    //也就是该资源的媒体类型
            $newName = $newName = md5(date('ymdhis') . $clientName) . "." . $entension;    //定义上传文件的新名称
            $path = $file->move('storage/uploads/' . $fileType, $newName);    //把缓存文件移动到制定文件夹
            return ['code' => 0, 'data' => $path];
        }
        return ['code' => -1];
    }
}

/**
 * 换算年龄
 * @param $birth
 * @return mixed
 */

if(!function_exists('howOld')){
    function howOld($birth) {
        if(is_string($birth)){
            list($birthYear, $birthMonth, $birthDay) = explode('-', $birth);
        }else{
            list($birthYear, $birthMonth, $birthDay) = explode('-', date('Y-m-d', $birth));
        }

        list($currentYear, $currentMonth, $currentDay) = explode('-', date('Y-m-d'));
        $age = $currentYear - $birthYear - 1;
        if($currentMonth > $birthMonth || $currentMonth == $birthMonth && $currentDay >= $birthDay)
            $age++;

        return $age;
    }
}



