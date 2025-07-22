<?php
namespace bingher\ding;

/**
 * 钉钉MarkDown文本格式化
 */
class MarkDown
{
    public static function h1(string $title)
    {
        return '# ' . $title . '\n';
    }
    public static function h2(string $title)
    {
        return '## ' . $title . '\n';
    }
    public static function h3(string $title)
    {
        return '### ' . $title . '\n';
    }
    public static function h4(string $title)
    {
        return '#### ' . $title . '\n';
    }
    public static function h5(string $title)
    {
        return '##### ' . $title . '\n';
    }
    public static function h6(string $title)
    {
        return '###### ' . $title . '\n';
    }

    public static function quote(string $content)
    {
        return '> ' . $content;
    }

    public static function bold(string $content)
    {
        return '**' . $content . '**';
    }

    public static function italics(string $content)
    {
        return '*' . $content . '*';
    }

    public static function link(string $content, string $url)
    {
        return '[' . $content . '](' . $url . ')';
    }

    public static function image(string $url, string $tip = '')
    {
        return '![' . $tip . '](' . $url . ')';
    }

    public static function ul($list)
    {
        if (is_string($list)) {
            return '- ' . $list;
        }
        if (is_array($list)) {
            $result = '';
            foreach ($list as $v) {
                $result = $result . '- ' . $v . '\n';
            }
            return $result;
        }
    }

    public static function ol($list, int $start = 1)
    {
        if (is_string($list)) {
            return $start . '. ' . $list;
        }
        if (is_array($list)) {
            $result = '';
            foreach ($list as $k => $v) {
                $result = $result . $start . '. ' . $v . '\n';
                $start++;
            }
            return $result;
        }
    }
}
