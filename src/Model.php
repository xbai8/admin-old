<?php

namespace Hangpu8\Admin;

use support\Model as SupportModel;

/**
 * @title 数据库基类
 * @desc 控制器描述
 * @author 楚羽幽 <admin@hangpu.net>
 */
class Model extends SupportModel
{
    // 设置数据库连接方式
    protected $connection = 'plugin.hangpu8.admin.mysql';

    // 自动时间戳
    public $timestamps = true;
    // 定义时间戳字段名
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    // 字段类型
    protected $casts = [
        'create_at'     => 'datetime:Y-m-d H:i:s',
        'update_at'     => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * 重写获取表名称
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->table ?? $this->uncamelize(class_basename($this));
    }

    /**
     * 驼峰命名转下划线命名
     *
     * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
     * @param string $camelCaps
     * @param string $separator
     * @return string
     */
    private function uncamelize(string $camelCaps, $separator = '_'): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
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
    private function camelize(string $uncamelized_words, $separator = '_'): string
    {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }
}
