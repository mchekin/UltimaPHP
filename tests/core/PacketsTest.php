<?php declare(strict_types=1);


namespace UltimaPHP\Tests\core;


use Packets;
use PacketsDefs;
use PHPUnit\Framework\TestCase;

class PacketsTest extends TestCase
{
    public function testSetPacketWithoutPacketId()
    {
        $sut = new Packets();

        self::assertFalse($sut->setPacket());
    }

    public function testSetPacket()
    {
        $packetId = 0x01;
        $packetLength = PacketsDefs::LENGTH[$packetId];

        $sut = new Packets();

        self::assertNull($sut->setPacket($packetId));
        self::assertSame($packetId, $sut->getPacket());
        self::assertSame($packetLength, $sut->getLength());
    }
}