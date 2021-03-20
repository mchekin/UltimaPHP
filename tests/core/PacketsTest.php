<?php declare(strict_types=1);


namespace UltimaPHP\Tests\core;


use Functions;
use Packets;
use PacketsDefs;
use PHPUnit\Framework\TestCase;

class PacketsTest extends TestCase
{
    public function test_set_packet_without_packet_id()
    {
        $sut = new Packets();

        self::assertFalse($sut->setPacket());
    }

    public function test_set_packet()
    {
        $packetId = 0x01;
        $packetLength = PacketsDefs::LENGTH[$packetId];

        $sut = new Packets();

        self::assertNull($sut->setPacket($packetId));
        self::assertSame($packetId, $sut->getPacket());
        self::assertSame($packetLength, $sut->getLength());
    }

    public function test_set_length_without_length_parameter()
    {
        $sut = new Packets();

        $sut->setLength();

        self::assertSame(-1, $sut->getLength());
    }

    public function test_set_length()
    {
        $packetLength = 7;

        $sut = new Packets();

        $sut->setLength($packetLength);

        self::assertSame($packetLength, $sut->getLength());
    }

    public function test_add_text()
    {
        $text = "how do you do?";

        $sut = new Packets();

        $sut->addText($text);

        self::assertSame("00" . Functions::strToHex($text), $sut->getPacketStr());
    }

    public function test_add_hex_string()
    {
        $hexString = "AA0068FF";

        $sut = new Packets();

        $sut->addHexStr($hexString);

        self::assertSame("00" . $hexString, $sut->getPacketStr());
    }

    public function test_add_hex_string_with_false_length()
    {
        $hexString = "AA0068FFB2";

        $sut = new Packets();

        $sut->addHexStr($hexString);

        $sut->setLength(false);

        $hexPrefix = $this->getHexPrefix($hexString);

        self::assertSame("00" . $hexPrefix . $hexString, $sut->getPacketStr());
    }

    public function test_add_text_with_false_length()
    {
        $text = "What is going on?";

        $sut = new Packets();

        $sut->addText($text);

        $sut->setLength(false);

        $hexString = Functions::strToHex($text);
        $hexPrefix = $this->getHexPrefix($hexString);

        self::assertSame("00" . $hexPrefix . $hexString, $sut->getPacketStr());
    }

    public function test_int8_packs_integer_into_hex_string()
    {
        self::assertSame('20', Packets::int8(32));
    }

    public function test_int8_unpacks_hex_string_into_integer()
    {
        self::assertSame(66, Packets::int8('B'));
    }

    public function test_u_int8_packs_integer_into_hex_string()
    {
        self::assertSame('20', Packets::uInt8(32));
    }

    public function test_u_int8_unpacks_hex_string_into_integer()
    {
        self::assertSame(66, Packets::uInt8('B'));
    }

    public function test_int16_packs_integer_into_hex_string()
    {
        self::assertSame('0020', Packets::int16(32));
    }

    public function test_int16_unpacks_hex_string_into_integer()
    {
        self::assertSame(66, Packets::int8('B'));
    }

    public function test_u_int16_with_little_endian_packs_integer_into_hex_string()
    {
        $integer = 3245;
        $expectedhexString = pack('v', $integer);

        self::assertSame($expectedhexString, Packets::uInt16($integer));
    }

    public function test_u_int16_with_big_endian_packs_integer_into_hex_string()
    {
        $integer = 3245;
        $expectedhexString = pack('n', $integer);

        self::assertSame($expectedhexString, Packets::uInt16($integer, true));
    }

    public function test_u_int16_with__machine_byte_order_packs_integer_into_hex_string()
    {
        $integer = 3245;
        $expectedhexString = pack('S', $integer);

        self::assertSame($expectedhexString, Packets::uInt16($integer, null));
    }

    public function test_u_int16_with_little_endian_unpacks_hex_string_into_integer()
    {
        $hexString = 'ABCDEF';

        self::assertSame(16961, Packets::uInt16($hexString));
    }

    public function test_u_int16_with_big_endian_unpacks_hex_string_into_integer()
    {
        $hexString = 'ABCDEF';

        self::assertSame(16706, Packets::uInt16($hexString, true));
    }

    public function test_u_int16_with_machine_byte_order_unpacks_hex_string_into_integer()
    {
        $hexString = pack("S", 16961);

        self::assertSame(16961, Packets::uInt16($hexString, null));
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