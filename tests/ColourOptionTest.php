<?php

/*
 * This file is part of composer/xdebug-handler.
 *
 * (c) Composer <https://github.com/composer>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Composer\XdebugHandler;

use Composer\XdebugHandler\XdebugHandler;
use PHPUnit\Framework\TestCase;

/**
 * This class does not need to extend Helpers\BaseTestCase
 */
class ColorOptionTest extends TestCase
{
    private $method;
    private $xdebug;

    public function testOptionNeeded()
    {
        $args = array('script.php', 'param');

        $result = $this->addColorOption($args, '--colors');
        $this->assertContains('--colors', $result);

        $args = array('script.php', 'param');

        $result = $this->addColorOption($args, '--colors=always');
        $this->assertContains('--colors=always', $result);
    }

    public function testOptionReplaced()
    {
        $args = array('script.php', 'param', '--color=auto');

        $result = $this->addColorOption($args, '--color=always');
        $this->assertContains('--color=always', $result);
        $this->assertNotContains('--color=auto', $result);
    }

    public function testOptionNotNeeded()
    {
        $args = array('script.php', 'param', '--no-ansi');

        $result = $this->addColorOption($args, '--ansi');
        $this->assertContains('--no-ansi', $result);
        $this->assertNotContains('--ansi', $result);

        $args = array('script.php', 'param', '--colors=something');

        $result = $this->addColorOption($args, '--colors=always');
        $this->assertContains('--colors=something', $result);
        $this->assertNotContains('--colors=always', $result);
    }

    public function testOptionNotMatched()
    {
        $args = array('script.php', 'param');

        $result = $this->addColorOption($args, '---ansi');
        $this->assertNotContains('---ansi', $result);

        $args = array('script.php', 'param');

        $result = $this->addColorOption($args, '---colors=always');
        $this->assertNotContains('---colors=always', $result);
    }

    protected function setUp()
    {
        $this->xdebug = new XdebugHandler('test');
        $class = new \ReflectionClass($this->xdebug);
        $this->method = $class->getMethod('addColorOption');
        $this->method->setAccessible(true);
    }

    private function addColorOption(array $args, $colorOption)
    {
        return $this->method->invoke($this->xdebug, $args, $colorOption);
    }
}
