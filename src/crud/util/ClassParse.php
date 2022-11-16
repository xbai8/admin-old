<?php

namespace Hangpu8\Admin\crud\util;

trait ClassParse
{
    /**
     * 解析类
     *
     * @param string $class
     * @return object
     */
    public function parse(string $class = ''): object
    {
        if (!$class) {
            $class = request()->controller;
        }
        return new \ReflectionClass($class);
    }

    /**
     * 解析类属性
     *
     * @param string $class
     * @return array
     */
    public function getProperties(string $class = ''): array
    {
        $class = $this->parse();
        $properties = $class->getDefaultProperties();
        return $properties;
    }

    /**
     * 解析类内部方法列表
     *
     * @param string $class
     * @return array
     */
    public function getClassMethods(string $class = ''): array
    {
        $class = $this->parse($class);
        $properties = $class->getMethods();
        return $properties;
    }

    /**
     * 解析类内部单个方法
     *
     * @param string $class
     * @param string $method
     * @return Object
     */
    public function getClassMethod(string $class = '', string $method): Object
    {
        $class = $this->parse($class);
        $method = $class->getMethod($method);
        return $method;
    }
}
