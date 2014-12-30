<?php

namespace TCP;

use Mib\Component\Network\SocketInterface;
use Mib\Component\Network\TCP\Socket;

class SocketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SocketInterface
     */
    private $socket;

    public function setUp()
    {
        $this->socket = new Socket();
    }

    public function tearDown()
    {
        unset($this->socket);
    }

    public function testConnectThrowsWhenConnectionToAnNonExistingServer()
    {
        $this->setExpectedException('\RuntimeException');
        $this->socket->connect('777.777.777.777', 6600);
    }

    public function testCanReadFromConnectedServer()
    {
        // connect to mpd server
        $result = $this->socket->connect('192.168.1.107', 6600);

        // first response will look something like: "OK MPD 0.16.0"

        $data = $this->socket->read(SocketInterface::BUFFER_SIZE, SocketInterface::RD_TEXT);

        $okString = 'OK MPD';

        $this->assertEquals(0, strncasecmp($okString, $data, strlen($okString)));
    }

    public function testCanWriteSendDataToConnectedServer()
    {
        $server = 'localhost';

        $this->socket->connect($server, 6600);

        $data = $this->socket->read();

        $okString = 'OK MPD';

        $this->assertEquals(0, strncasecmp($okString, $data, strlen($okString)));

        $this->socket->write("status\n");

        $data = $this->socket->read();

        $lines = explode(PHP_EOL, trim($data,"\n\t "));

        $this->assertEquals('OK', end($lines));
    }
}