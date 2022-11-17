<?php

namespace Hangpu8\Admin\install;

/**
 * @title 数据库相关操作
 * @desc 安装时的数据库相关操作
 * @author 楚羽幽 <admin@hangpu.net>
 */
class AppDatabase
{
    /**
     * 去除sql文件中的注释
     *
     * @param $sql
     * @return string
     */
    public static function removeComments($sql): string
    {
        return preg_replace("/(\n--[^\n]*)/", "", $sql);
    }

    /**
     * 替换表前缀
     *
     * @param string $sql
     * @param string $prefix
     * @return string
     */
    public static function strReplacePrefix(string $sql, string $prefix): string
    {
        return str_replace("php_", $prefix, $sql);
    }

    /**
     * @param $sql
     * @param $delimiter
     * @return array
     */
    public static function splitSqlFile($sql, $delimiter): array
    {
        $tokens = explode($delimiter, $sql);
        $output = array();
        $matches = array();
        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; $i++) {
            if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
                $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
                $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
                $unescaped_quotes = $total_quotes - $escaped_quotes;

                if (($unescaped_quotes % 2) == 0) {
                    $output[] = $tokens[$i];
                    $tokens[$i] = "";
                } else {
                    $temp = $tokens[$i] . $delimiter;
                    $tokens[$i] = "";

                    $complete_stmt = false;
                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
                        $unescaped_quotes = $total_quotes - $escaped_quotes;
                        if (($unescaped_quotes % 2) == 1) {
                            $output[] = $temp . $tokens[$j];
                            $tokens[$j] = "";
                            $temp = "";
                            $complete_stmt = true;
                            $i = $j;
                        } else {
                            $temp .= $tokens[$j] . $delimiter;
                            $tokens[$j] = "";
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * 获取pdo连接
     *
     * @param $host
     * @param $username
     * @param $password
     * @param $port
     * @param $database
     * @return \PDO
     */
    public static function getPdo($host, $username, $password, $port, $database = null): \PDO
    {
        $dsn = "mysql:host=$host;port=$port;";
        if ($database) {
            $dsn .= "dbname=$database";
        }
        $params = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8mb4",
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_TIMEOUT => 5,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];
        return new \PDO($dsn, $username, $password, $params);
    }
}
