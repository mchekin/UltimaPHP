<?php declare(strict_types=1);


namespace UltimaPHP\Tests\core;


use Functions;
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

    public function testSetLengthWithoutLengthParameter()
    {
        $sut = new Packets();

        $sut->setLength();

        self::assertSame(-1, $sut->getLength());
    }

    public function testSetLength()
    {
        $packetLength = 7;

        $sut = new Packets();

        $sut->setLength($packetLength);

        self::assertSame($packetLength, $sut->getLength());
    }

    public function testAddText()
    {
        $text = "how do you do?";

        $sut = new Packets();

        $sut->addText($text);

        self::assertSame("00" . Functions::strToHex($text), $sut->getPacketStr());
    }

    public function testAddHexString()
    {
        $hexString = "AA0068FF";

        $sut = new Packets();

        $sut->addHexStr($hexString);

        self::assertSame("00" . $hexString, $sut->getPacketStr());
    }

    public function testAddHexStringWithFalseLength()
    {
        $hexString = "AA0068FFB2";

        $sut = new Packets();

        $sut->addHexStr($hexString);

        $sut->setLength(false);

        $hexPrefix = $this->getHexPrefix($hexString);

        self::assertSame("00" . $hexPrefix . $hexString, $sut->getPacketStr());
    }

    public function testAddTextWithFalseLength()
    {
        $text = "What is going on?";

        $sut = new Packets();

        $sut->addText($text);

        $sut->setLength(false);

        $hexString = Functions::strToHex($text);
        $hexPrefix = $this->getHexPrefix($hexString);

        self::assertSame("00" . $hexPrefix . $hexString, $sut->getPacketStr());
    }

    public function testInt8WithIntegerGetHexadecimalString()
    {
        self::assertSame('20', Packets::int8(32));
    }

    public function testInt8WithStringGetAsciiValueOfTheFirstCharacter()
    {
        self::assertSame(66, Packets::int8('Bold'));
    }

    public function testUInt8WithIntegerGetHexadecimalString()
    {
        self::assertSame('20', Packets::uInt8(32));
    }

    public function testUInt8WithStringGetAsciiValueOfTheFirstCharacter()
    {
        self::assertSame(66, Packets::uInt8('Bold'));
    }

    public function testInt16WithIntegerGetHexadecimalString()
    {
        self::assertSame('0020', Packets::int16(32));
    }

    public function testInt16WithStringGetAsciiValueOfTheFirstTwoCharacters()
    {
        self::assertSame(28482, Packets::int16('Bold'));
    }

    private function getHexPrefix(string $hexString): string
    {
        return str_pad(
                dechex((strlen($hexString) / 2) + 3),
                4,
                "0",
                STR_PAD_LEFT
            );
    }
}