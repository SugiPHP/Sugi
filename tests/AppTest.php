<?php
/**
 * Tests for SugiPHP Sugi Class
 *
 * @package SugiPHP.Sugi
 * @author  Plamen Popov <tzappa@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Sugi\App;
use PHPUnit_Framework_TestCase;
use StdClass;

class AppTest extends PHPUnit_Framework_TestCase
{
    public function testCreateAndOneInstance()
    {
        $app = new App();
        $this->assertInstanceOf("SugiPHP\Sugi\App", $app);
        $sameApp = App::getInstance();
        $this->assertSame($sameApp, $app);
    }

    public function testLogger()
    {
        $app = new App();
        // check default settings
        $this->assertSame($app["log_path"].date("Y-m-d").".log", $app["logger"]->getFilename());
        $this->assertSame($app["debug"] ? "debug" : "info", $app["logger"]->getLevel());
        // changing logger settings
        $app["logger"]->setFilename("");
        $this->assertSame("", $app["logger"]->getFilename());
        $app["logger"]->setLevel("error");
        $this->assertSame("error", $app["logger"]->getLevel());
    }

    public function testDefaultCache()
    {
        $app = new App();
        // int
        $app->cache("one", 1);
        $this->assertSame(1, $app->cache("one"));
        // array
        $app->cache("arr", ["foo" => "bar"]);
        $this->assertSame(["foo" => "bar"], $app->cache("arr"));
        // no value
        $this->assertNull($app->cache("two"));
    }

    public function testNullCache()
    {
        $app = new App(["cache" => new \SugiPHP\Cache\Cache(new \SugiPHP\Cache\NullStore())]);
        $app->cache("one", 1);
        // no value is actually stored
        $this->assertNull($app->cache("one"));
        $app->cache("arr", ["foo" => "bar"]);
        // no value is actually stored
        $this->assertNull($app->cache("arr"));
        // no value
        $this->assertNull($app->cache("two"));
    }
}
