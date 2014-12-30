<?php

namespace UDP;

class SocketTest extends \PHPUnit_Framework_Testcase
{
    private $serverHost = '127.0.0.1';
    private $serverPort = 12345;

    private $serverResource;

    /** @var \Mib\Component\Network\UDP\Socket */
    private $socket;

    public function setUp()
    {
        // create local test udp server
        $resource = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (false === $resource || false === socket_bind($resource, $this->serverHost, $this->serverPort))
            throw new \RuntimeException(sprintf("unable to create test server %s:%d", $this->serverHost, $this->serverPort));
        $this->serverResource = $resource;

        // create system under test
        $this->socket = new \Mib\Component\Network\UDP\Socket();
    }

    public function tearDown()
    {
        // close local test server socket
        socket_close($this->serverResource);
    }

    private function readFromTestServer($length = 4096)
    {
        return socket_read($this->serverResource, $length);
    }

    public function testCanSendToLocalTestServer()
    {
        $message = 'HELLO THIS IS A TEST: 0123456789';

        $this->socket->sendTo($message, strlen($message), $this->serverHost, $this->serverPort);

        $result = $this->readFromTestServer(strlen($message));

        $this->assertEquals($message, $result);
    }
}