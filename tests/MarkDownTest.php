<?php declare (strict_types = 1);

use bingher\ding\MarkDown;
use PHPUnit\Framework\TestCase;

final class MarkDownTest extends TestCase
{
    public function testH1()
    {
        $this->assertEquals(MarkDown::h1('hello'), '# hello\n');
    }
    public function testH2()
    {
        $this->assertEquals(MarkDown::h2('hello'), '## hello\n');
    }
    public function testH3()
    {
        $this->assertEquals(MarkDown::h3('hello'), '### hello\n');
    }
    public function testH4()
    {
        $this->assertEquals(MarkDown::h4('hello'), '#### hello\n');
    }
    public function testH5()
    {
        $this->assertEquals(MarkDown::h5('hello'), '##### hello\n');
    }
    public function testH6()
    {
        $this->assertEquals(MarkDown::h6('hello'), '###### hello\n');
    }

    public function testQuote()
    {
        $this->assertEquals(MarkDown::quote('hello'), '> hello');
    }
    public function testBold()
    {
        $this->assertEquals(MarkDown::bold('hello'), '**hello**');
    }
    public function testItalics()
    {
        $this->assertEquals(MarkDown::italics('hello'), '*hello*');
    }
    public function testLink()
    {
        $this->assertEquals(
            MarkDown::link('hello', 'https://example.com'),
            '[hello](https://example.com)'
        );
    }
    public function testImage()
    {
        $this->assertEquals(
            MarkDown::image('https://example.com', 'hello'),
            '![hello](https://example.com)'
        );
    }
    public function testsUl()
    {
        $this->assertEquals(MarkDown::ul('hello'), '- hello');
        $this->assertEquals(
            MarkDown::ul(['a', 'b', 'c']),
            '- a\n- b\n- c\n'
        );
    }

    public function testOl()
    {
        $this->assertEquals(MarkDown::ol('hello'), '1. hello');
        $this->assertEquals(
            MarkDown::ol(['a', 'b', 'c']),
            '1. a\n2. b\n3. c\n'
        );
        $this->assertEquals(
            MarkDown::ol(['a', 'b', 'c'], 3),
            '3. a\n4. b\n5. c\n'
        );
    }

    /**
     * @depends testH1
     * @depends testH2
     *
     * @param [type] $h1
     * @param [type] $h2
     *
     * @return void
     */
    public function testComponent($h1, $h2)
    {
        $res = MarkDown::ol([$h1, $h2]);
        $this->assertEquals(
            $res,
            '1. # hello\n2. ## hello\n'
        );
    }
}
