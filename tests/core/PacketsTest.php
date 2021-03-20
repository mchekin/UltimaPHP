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

    public function test_add_u_int_8()
    {
        $integer = 66;

        $sut = new Packets();

        $sut->addUInt8($integer);

        self::assertSame("00" . dechex($integer), $sut->getPacketStr());
    }

    public function test_add_int_8()
    {
        $integer = 66;

        $sut = new Packets();

        $sut->addInt8($integer);

        self::assertSame("00" . dechex($integer), $sut->getPacketStr());
    }

    public function test_add_u_int_16()
    {
        $hexString = 'AB';

        $sut = new Packets();

        $sut->addUInt16($hexString);

        self::assertSame("004241", $sut->getPacketStr());
    }

    public function test_add_int_16()
    {
        $integer = 66;

        $sut = new Packets();

        $sut->addInt16($integer);

        self::assertSame("0000" . dechex($integer), $sut->getPacketStr());
    }

    public function test_add_u_int_32()
    {
        $hexString = 'ABCD';

        $sut = new Packets();

        $sut->addUInt32($hexString);

        self::assertSame("001145258561", $sut->getPacketStr());
    }

    public function test_add_int_32()
    {
        $integer = 1145258561;

        $sut = new Packets();

        $sut->addInt32($integer);

        self::assertSame("00" . dechex($integer), $sut->getPacketStr());
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
        self::assertSame(16706, Packets::int16('BA'));
    }

    public function test_u_int16_with_little_endian_packs_integer_into_hex_string()
    {
        $integer = 3245;
        $expectedHexString = pack('v', $integer);

        self::assertSame($expectedHexString, Packets::uInt16($integer));
    }

    public function test_u_int16_with_big_endian_packs_integer_into_hex_string()
    {
        $integer = 3245;
        $expectedHexString = pack('n', $integer);

        self::assertSame($expectedHexString, Packets::uInt16($integer, true));
    }

    public function test_u_int16_with_machine_byte_order_packs_integer_into_hex_string()
    {
        $integer = 3245;
        $expectedHexString = pack('S', $integer);

        self::assertSame($expectedHexString, Packets::uInt16($integer, null));
    }

    public function test_u_int16_with_little_endian_unpacks_hex_string_into_integer()
    {
        self::assertSame(16961, Packets::uInt16('AB'));
    }

    public function test_u_int16_with_big_endian_unpacks_hex_string_into_integer()
    {
        self::assertSame(16706, Packets::uInt16('AB', true));
    }

    public function test_u_int16_with_machine_byte_order_unpacks_hex_string_into_integer()
    {
        $hexString = pack("S", 16961);

        self::assertSame(16961, Packets::uInt16($hexString, null));
    }

    public function test_int32_packs_integer_into_hex_string()
    {
        self::assertSame('0102CFB0', Packets::int32(16961456));
    }

    public function test_u_int32_with_little_endian_packs_integer_into_hex_string()
    {
        $integer = 1145258561;
        $expectedHexString = pack('V', $integer);

        self::assertSame($expectedHexString, Packets::uInt32($integer));
    }

    public function test_u_int32_with_big_endian_packs_integer_into_hex_string()
    {
        $integer = 1145258561;
        $expectedHexString = pack('N', $integer);

        self::assertSame($expectedHexString, Packets::uInt32($integer, true));
    }

    public function test_u_int32_with__machine_byte_order_packs_integer_into_hex_string()
    {
        $integer = 1145258561;
        $expectedHexString = pack('L', $integer);

        self::assertSame($expectedHexString, Packets::uInt32($integer, null));
    }

    public function test_u_int32_with_little_endian_unpacks_hex_string_into_integer()
    {
        self::assertSame(1145258561, Packets::uInt32('ABCD'));
    }

    public function test_u_int32_with_big_endian_unpacks_hex_string_into_integer()
    {
        self::assertSame(1094861636, Packets::uInt32('ABCD', true));
    }

    public function test_u_int32_with_machine_byte_order_unpacks_hex_string_into_integer()
    {
        $hexString = pack("L", 1094861636);

        self::assertSame(1094861636, Packets::uInt32($hexString, null));
    }

    public function test_int64_packs_integer_into_hex_string()
    {
        self::assertSame('ABCDEFBA', Packets::int64(4702398224240165441));
    }

    public function test_u_int64_with_little_endian_packs_integer_into_hex_string()
    {
        $integer = 4702398224240165441;
        $expectedHexString = pack('P', $integer);

        self::assertSame($expectedHexString, Packets::uInt64($integer));
    }

    public function test_u_int64_with_big_endian_packs_integer_into_hex_string()
    {
        $integer = 4702398224240165441;
        $expectedHexString = pack('J', $integer);

        self::assertSame($expectedHexString, Packets::uInt64($integer, true));
    }

    public function test_u_int64_with__machine_byte_order_packs_integer_into_hex_string()
    {
        $integer = 4702398224240165441;
        $expectedHexString = pack('Q', $integer);

        self::assertSame($expectedHexString, Packets::uInt64($integer, null));
    }

    public function test_u_int64_with_little_endian_unpacks_hex_string_into_integer()
    {
        self::assertSame(4702398224240165441, Packets::uInt64('ABCDEFBA'));
    }

    public function test_u_int64_with_big_endian_unpacks_hex_string_into_integer()
    {
        self::assertSame(4702394921427288641, Packets::uInt64('ABCDEFBA', true));
    }

    public function test_u_int64_with_machine_byte_order_unpacks_hex_string_into_integer()
    {
        $hexString = pack("Q", 4702398224240165441);

        self::assertSame(4702398224240165441, Packets::uInt64($hexString, null));
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