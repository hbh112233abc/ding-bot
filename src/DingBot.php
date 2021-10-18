<?php
declare (strict_types = 1);
namespace bingher\ding;

class DingBot
{
    /**
     * 钉钉自定义机器人接口链接
     * @var string
     */
    protected $webhook = '';

    /**
     * 设置签名密钥
     * @var string
     */
    protected $secret = '';

    /**
     * 关键词
     * @var string
     */
    protected $keyword = '';

    /**
     * 消息类型
     * @var string
     */
    protected $msgType = 'text';
    /**
     * @信息
     * @var array
     */
    protected $at = [];
    /**
     * 链接数组
     * @var array
     */
    protected $links = [];
    /**
     * 按钮数组
     * @var array
     */
    protected $btns = [];
    /**
     * 错误消息
     * @var string
     */
    protected $error = '';
    public function __construct(array $config = [])
    {
        $this->webhook = $config['webhook'] ?? '';
        if (empty($this->webhook)) {
            throw new \Exception('webhook not setting');
        }
        $this->secret  = $config['secret'] ?? '';
        $this->keyword = $config['keyword'] ?? '';
    }

    /**
     * 初始化
     * @return $this
     */
    public function init()
    {
        $this->at    = [];
        $this->links = [];
        $this->btns  = [];
        return $this;
    }

    /**
     * 设置配置
     * @param array $config 配置信息数组
     */
    public function setConfig(array $config = [])
    {
        if (!empty($config['webhook'])) {
            $this->webhook = $config['webhook'];
        }
        return $this;
    }

    /**
     * 发送给手机号
     * @param  string,array  $mobiles 接收人手机号
     * 接收三种类型传参:
     * 1:139xxxxx163
     * 2:139xxxxx161,139xxxxx162,139xxxxx163
     * 3:[139xxxxx161,139xxxxx162,139xxxxx163]
     * @return $this
     */
    public function at($mobiles)
    {
        if (empty($mobiles)) {
            return $this;
        }
        if (is_string($mobiles)) {
            if (strpos($mobiles, ',')) {
                $mobiles = explode(',', $mobiles);
            } else {
                $mobiles = [$mobiles];
            }
        }

        if (empty($this->at)) {
            $this->at = [
                'atMobiles' => [],
                'isAtAll'   => false,
            ];
        }
        $this->at['atMobiles'] = array_merge($this->at['atMobiles'], $mobiles);
        return $this;
    }

    /**
     * @所有人
     * @return $this
     */
    public function atAll()
    {
        $this->at = [
            'isAtAll' => true,
        ];
        return $this;
    }

    /**
     * 发送文本消息
     * @param  string $content 消息内容
     * @return array    发送结果
     */
    public function text(string $content = '')
    {
        $this->msgType = 'text';
        $data          = [
            'text' => [
                'content' => $content,
            ],
        ];
        return $this->sendMsg($data);
    }

    /**
     * 发送链接消息
     * @param  string $title   标题文本
     * @param  string $content 内容文本
     * @param  string $linkUrl 跳转链接url
     * @param  string $picUrl  附图url
     * @return array    发送结果
     */
    public function link(string $title, string $content, string $linkUrl, string $picUrl = '')
    {
        $this->msgType = 'link';
        $data          = [
            'link' => [
                'title'      => $title,
                'text'       => $content,
                'messageUrl' => $linkUrl,
                'picUrl'     => $picUrl,
            ],
        ];
        return $this->sendMsg($data);
    }

    /**
     * markdown消息
     * @param  string $title   标题
     * @param  string $content 内容
     * @return [type]          [description]
     */
    public function markdown(string $title, string $content)
    {
        $this->msgType = 'markdown';
        $data          = [
            'markdown' => [
                'title' => $title,
                'text'  => $content,
            ],
        ];
        return $this->sendMsg($data);
    }

    /**
     * 整体跳转ActionCard类型消息
     * @param  string      $title          标题
     * @param  string      $content        摘要
     * @param  string      $url            链接地址
     * @param  string      $picUrl         预览图片地址
     * @param  string      $singleTitle    跳转按钮标题
     * @param  int|integer $btnOrientation 按钮排列 0:竖排,1:横排
     * @param  int|integer $hideAvatar     头像显示 0:隐藏,1:显示
     * @return [type]                      [description]
     */
    public function singleActionCard(
        string $title,
        string $content,
        string $url,
        string $picUrl = '',
        string $singleTitle = '阅读全文',
        int $btnOrientation = 0,
        int $hideAvatar = 1
    ) {
        $this->msgType = 'actionCard';
        if (!empty($picUrl)) {
            $content = "![screenshot]({$picUrl})" . $content;
        }
        $data = [
            'actionCard' => [
                'title'          => $title,
                'text'           => $content,
                'singleTitle'    => $singleTitle,
                'singleURL'      => $url,
                'btnOrientation' => $btnOrientation,
                'hideAvatar'     => $hideAvatar,
            ],
        ];
        return $this->sendMsg($data);
    }

    /**
     * 组装按钮btn
     * @param  string $title 按钮标题
     * @param  string $url   跳转链接
     * @return [type]        [description]
     */
    public function makeBtn(string $title, string $url)
    {
        $this->btns[] = [
            'title'     => $title,
            'actionURL' => $url,
        ];
        return $this;
    }

    /**
     * 独立跳转ActionCard类型消息
     * @param  string      $title          标题
     * @param  string      $content        摘要
     * @param  array       $btns           按钮数组
     * @param  int|integer $btnOrientation 按钮排列 0:竖排,1:横排
     * @param  int|boolean $hideAvatar     头像显示 0:隐藏,1:显示
     * @return [type]                      [description]
     */
    public function multiActionCard(
        string $title,
        string $content,
        array $btns = [],
        int $btnOrientation = 1,
        int $hideAvatar = 0
    ) {
        $this->msgType = 'actionCard';
        if (empty($btns)) {
            $btns = $this->btns;
        }
        $data = [
            'actionCard' => [
                'title'          => $title,
                'text'           => $content,
                'btns'           => $btns,
                'btnOrientation' => $btnOrientation,
                'hideAvatar'     => $hideAvatar,
            ],
        ];
        return $this->sendMsg($data);
    }

    /**
     * 组装link
     * @param  string $title  标题
     * @param  string $msgUrl 消息链接
     * @param  string $picUrl 图片链接
     * @return $this
     */
    public function makeLink(string $title, string $msgUrl, string $picUrl)
    {
        $this->links[] = [
            'title'      => $title,
            'messageURL' => $msgUrl,
            'picURL'     => $picUrl,
        ];
        return $this;
    }

    /**
     * feedCard类型消息
     * @param  array  $links 链接消息数组
     * @return [type]        [description]
     */
    public function feedCard(array $links = [])
    {
        if (empty($links)) {
            $links = $this->links;
        }
        if (empty($links)) {
            throw new \Exception('links empty');
        }
        $this->msgType = 'feedCard';
        $data          = [
            'feedCard' => [
                'links' => $links,
            ],
        ];
        return $this->sendMsg($data);
    }

    /**
     * 组装发送消息
     * @param  array  $data 消息内容数组
     * @return array       发送结果
     */
    public function sendMsg(array $data)
    {
        if (empty($data['msgtype'])) {
            $data['msgtype'] = $this->msgType;
        }
        if (empty($data['at'])) {
            $data['at'] = $this->at;
        }
        $this->init();
        $res    = $this->request($data);
        $result = json_decode($res, true);
        if ($result['errcode'] !== 0) {
            $this->error = $result['errmsg'];
            return false;
        }
        return true;
    }

    /**
     * 获取错误信息
     * @return string 错误信息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 加签后的接口
     *
     * @param string $url
     * @return void
     */
    protected function signUrl(string $url): string
    {
        $time      = time() * 1000;
        $strToSign = $time . "\n" . $this->secret;
        $sign      = hash_hmac('sha256', $strToSign, $this->secret, true);
        $sign      = base64_encode($sign);
        $sign      = urlencode($sign);
        return $url . '&timestamp=' . $time . '&sign=' . $sign;
    }

    /**
     * 发送数据
     * @param  array $postData 发送消息数据数组
     * @return [type]           [description]
     */
    protected function request(array $postData)
    {
        $postStr = json_encode($postData);
        $ch      = curl_init();
        $apiUrl  = $this->webhook;
        if (!empty($this->secret)) {
            $apiUrl = $this->signUrl($this->webhook);
        }
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
