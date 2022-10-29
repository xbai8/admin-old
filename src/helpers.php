<?php

use think\Validate;

/**
 * 验证器（支持场景验证）
 *
 * @param Validate $validate
 * @param array $data
 * @param string $scene
 * @return boolean
 */
function hpValidate(Validate $validate, array $data, string $scene = ''): bool
{
    // 场景验证
    if ($scene) {
        $validate->scene($scene);
    }
    $result = $validate->check($data);
    if (!$result) {
        throw new Exception((string)$validate->getError());
    }
    return true;
}

/**
 * 读取配置项
 *
 * @param string $key
 * @param integer $cid
 * @return string|array
 */
function getHpConfig($key = '', $cid = 0)
{
    $model = new \Hangpu8\Admin\model\SystemWebconfig;
    $map = [];
    if ($cid) {
        $map['cid'] = $cid;
    }
    $data = [];
    if ($key) {
        $data = '';
        $map['name'] = $key;
        $info = $model->where($map)->find();
        if ($info) {
            switch ($info['type']) {
                case 'image':
                    $info['value'] = $info['value'] ? "/{$info['value']}" : '';
                    break;
                case 'images':
                    $images = explode(',', $info['value']);
                    foreach ($images as $key => $val) {
                        $info['value'][$key] = $val ? "/{$val}" : '';
                    }
                    break;
            }
            $data = $info['value'];
        } else {
            $data = [];
        }
    } else {
        $list = $model
            ->where($map)
            ->order('id asc')
            ->select();
        foreach ($list as $key => $value) {
            if ($value['type'] == 'image') {
                // 单图
                $data[$value['name']] = $value['value'] ? "/{$value['value']}" : '';
            } else if ($value['type'] == 'images') {
                //多图
                $images = explode(',', $value['value']);
                $imageArray = [];
                foreach ($images as $key => $val) {
                    $imageArray[$key] = "/{$val}";
                }
                $data[$value['name']] = $imageArray;
            } else {
                // 其他选项
                $data[$value['name']] = $value['value'];
            }
        }
    }
    return $data;
}
/**
 * [friend_date 友好时间显示]
 * @param  [type] $time [时间戳]
 * @return [type]       [description]
 */
function friend_date(int $time)
{
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = floor($d / 3600) . '小时前';
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日 H:i', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}
/**
 * 生成6位随机数
 *
 * @param integer $len
 * @return int
 */
function get_random($len = 6): int
{
    $unique_no = substr(base_convert(md5(uniqid(md5(microtime(true)), true)), 16, 10), 0, $len);
    return $unique_no;
}
/**
 * XML转数组
 *
 * @param string $xml
 * @return array
 */
function xmlToArr(string $xml): array
{
    //将xml转化为json格式
    $jsonxml = json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
    //转成数组
    $result = json_decode($jsonxml, true);

    // 返回数组
    return $result;
}

/**
 * 根据大小返回标准单位 KB  MB GB等
 *
 * @param integer $size
 * @param integer $decimals
 * @return string
 */
function get_size(int $size, int $decimals = 2): string
{
    switch (true) {
        case $size >= pow(1024, 3):
            return round($size / pow(1024, 3), $decimals) . " GB";
        case $size >= pow(1024, 2):
            return round($size / pow(1024, 2), $decimals) . " MB";
        case $size >= pow(1024, 1):
            return round($size / pow(1024, 1), $decimals) . " KB";
        default:
            return $size . 'B';
    }
}
/**
 * 下划线转驼峰
 *
 * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
 * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
 * @param string $uncamelized_words
 * @param string $separator
 * @return string
 */
function camelize(string $uncamelized_words, $separator = '_'): string
{
    $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
}
/**
 * 驼峰命名转下划线命名
 *
 * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
 * @param string $camelCaps
 * @param string $separator
 * @return string
 */
function uncamelize(string $camelCaps, $separator = '_'): string
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

/**
 * 当前登录管理员id
 *
 * @return mixed|null
 */
function admin_id()
{
    return session('admin.id');
}

/**
 * 当前管理员
 *
 * @param null|array|string $fields
 * @return array|mixed|null
 */
function admin($fields = null)
{
    if (!$admin = session('admin')) {
        return null;
    }
    if ($fields === null) {
        return $admin;
    }
    if (is_array($fields)) {
        $results = [];
        foreach ($fields as $field) {
            $results[$field] = $admin[$field] ?? null;
        }
        return $results;
    }
    return $admin[$fields] ?? null;
}

/**
 * 当前登录用户id
 *
 * @return mixed|null
 */
function user_id()
{
    return session('user.id');
}

/**
 * 当前登录用户
 *
 * @param null|array|string $fields
 * @return array|mixed|null
 */
function user($fields = null)
{
    if (!$user = session('user')) {
        return null;
    }
    if ($fields === null) {
        return $user;
    }
    if (is_array($fields)) {
        $results = [];
        foreach ($fields as $field) {
            $results[$field] = $user[$field] ?? null;
        }
        return $results;
    }
    return $user[$fields] ?? null;
}
