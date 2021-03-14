<?php declare(strict_types=1);


namespace UltimaPHP\Tests\core;


use Packets;
use PHPUnit\Framework\TestCase;

class PacketsTest extends TestCase
{
    public function testSetPacketWithoutPacketId()
    {
        $sut = new Packets();

        self::assertFalse($sut->setPacket());
    }
}