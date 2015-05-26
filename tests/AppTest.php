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
}
