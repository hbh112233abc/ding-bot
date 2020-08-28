<?php
require __DIR__ . '/src/DingBot.php';

$config = [
    //自定义机器人api接口链接
    'webhook' => 'https://oapi.dingtalk.com/robot/send?access_token=3991bb69865f79f86e9f16b659025bb39067cf4a764d0d4e3a8b32ff82935d93',
    'secret'  => 'SECb498bed28866ae406e7f378ac0125fd4bfa52d0dc0b32d1ddf2aba0e69a3306b',
];

//实例化
$ding = new \bingher\ding\DingBot($config);

//发送文本消息
$res = $ding->text('hello hello');
$res = $ding->at('18759201163')->at('18698313968')->text('hello hello');
$res = $ding->atAll()->text('hello hello');

//发送链接
$res = $ding->link('电子档案平台', '建设工程全过程资料无纸化平台', 'http://www.efileyun.com', 'http://www.efileyun.com/Public/images/banner_02.png');

//发送markdown
$res = $ding->markdown(
    '电子档案平台',
    "## 电子档案平台 \n **建设工程**全过程资料无纸化[档案平台](http://www.efileyun.com) \n ![宣传图片](http://www.efileyun.com/Public/images/banner_02.png)"
);

//发送actionCard
$res = $ding->singleActionCard('周星驰的龙套生涯', '台上一分钟，台下十年功。一点没错。从跑龙套变成星爷', 'https://v.qq.com/x/page/t09039nicle.html', 'https://abiko.loli.net/files/2019/07/29/ede7e2b6d13a41ddf9f4bdef84fdc737.png');

//发送actionCard
$res = $ding->makeBtn('周杰伦', 'http://tags.finance.sina.com.cn/%E5%91%A8%E6%9D%B0%E4%BC%A6')
    ->makeBtn('蔡徐坤', 'http://tags.finance.sina.com.cn/%E8%94%A1%E5%BE%90%E5%9D%A4')
    ->multiActionCard(
        '微博超话榜',
        "![st](https://p.ssl.qhimg.com/dmsmfl/120_75_/t01699b85fad2bf863d.webp?size=639x400&phash=2584806780411300945)## 微博超话榜 \n在过去前一周里,一群70后、80后、90后的 叔叔阿姨们 ,像打了鸡血一样,为周杰伦发起了一场 打榜之战"
    );

//发送feedCard
$res = $ding->makeLink(
    '这些工种，赚钱多多', 'https://bh.sb/post/46133/', 'https://abiko.loli.net/files/2019/07/29/b83aac23b9528732c23cc7352950e880.png')
    ->makeLink('男青年择偶指南', 'https://bh.sb/post/46123/', 'https://abiko.loli.net/files/2019/07/28/c8c41c4a18675a74e01c8a20e8a0f662.png')
    ->makeLink('你我所熟知的那个维基百科，出事情了', 'https://bh.sb/post/46120/', 'https://abiko.loli.net/thumb/?src=https://dulei.si/files/2019/07/28/006f52e9102a8d3be2fe5614f42ba989.jpeg&w=240&h=180&zc=1')
    ->feedCard();

var_dump($res);
var_dump($ding->getError());
