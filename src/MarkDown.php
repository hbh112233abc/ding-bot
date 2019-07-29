<?php
namespace bingher\ding;

/**
 * 钉钉MarkDown文本格式化
 */
class MarkDown
{
    static public function h1(string $title)
    {
        return '# '.$title.'\n';
    }
    static public function h2(string $title)
    {
        return '## '.$title.'\n';
    }
    static public function h3(string $title)
    {
        return '### '.$title.'\n';
    }
    static public function h4(string $title)
    {
        return '#### '.$title.'\n';
    }
    static public function h5(string $title)
    {
        return '##### '.$title.'\n';
    }
    static public function h6(string $title)
    {
        return '###### '.$title.'\n';
    }

    static public function quote(string $content)
    {
        return '> '.$content;
    }

    static public function bold(string $content)
    {
        return '**'.$content.'**';
    }

    static public function italics(string $content)
    {
        return '*'.$content.'*';
    }

    static public function link(string $content,string $url)
    {
        return '['.$content.']('.$url.')';
    }

    static public function image(string $url,string $tip = '')
    {
        return '!['.$tip.']('.$url.')';
    }

    static public function ul($list)
    {
        if (is_string($list)) {
            return '- '.$list;
        }
        if (is_array($list)) {
            $result = '';
            foreach ($list as $k => $v) {
                $result = $result . '- '.$v.'\n';
            }
            return $result;
        }
    }

    static public function ol($list,int $start = 1)
    {
        if (is_string($list)) {
            return $start.'. '.$list;
        }
        if (is_array($list)) {
            $result = '';
            foreach ($list as $k => $v) {
                $result = $result . $start.'. '.$v.'\n';
                $start ++;
            }
            return $result;
        }
    }
}