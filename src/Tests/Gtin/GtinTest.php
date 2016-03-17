<?php
Namespace Tests\Gtin;

require 'vendor/autoload.php';

use Gtin\Gtin;

class GtinTest extends \PHPUnit_Framework_TestCase
{
    protected $gtin;

    public function setUp()
    {
        $this->gtin = new Gtin();
    }

    public function testConstruct()
    {
        $this->assertEquals(get_class($this->gtin), "Gtin\Gtin");
    }

    public function testExceptions()
    {
        $this->assertEquals('{"1001":"No Gtin number set"}', $this->gtin->getFullGtinNumber());
        $this->assertEquals('{"1002":"Invalid Gtin number leght"}', $this->gtin->setGtinNumber(123));
        $this->assertEquals('{"1002":"Invalid Gtin number leght"}', $this->gtin->setGtinNumber('1234567A'));
    }

    public function testSetGtin()
    {
        $this->assertTrue($this->gtin->setGtinNumber(1234567));
        $this->assertTrue($this->gtin->setGtinNumber('1234567'));
        $this->assertTrue($this->gtin->setGtinNumber(12345678910));
        $this->assertTrue($this->gtin->setGtinNumber(123456789101));
        $this->assertTrue($this->gtin->setGtinNumber(1234567891010));
    }

    public function testChecksum()
    {
        $this->assertEquals(6, (int)$this->gtin->getChecksum(1234567));
    }

    public function testFullGtin()
    {
        $this->assertEquals(12345676, (int)$this->gtin->getFullGtinNumber(1234567));
    }
}
