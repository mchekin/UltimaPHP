<?php declare(strict_types=1);


namespace UltimaPHP\Tests\core\packets;


use packet_0xB1;
use PHPUnit\Framework\TestCase;

class packet_0xB1_test extends TestCase
{
    public function testReceive()
    {
        $sut = new packet_0xB1();

        $data = [];

        self::assertFalse($sut->receive($data));
    }
}