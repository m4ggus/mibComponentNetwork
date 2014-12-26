<?php

namespace Mib\Component\Network\UDP;

use Mib\Component\Network\AbstractSocket;
use Mib\Component\Network\SocketInterface;

/**
 * Class Socket
 * UDP Socket for sending and receiving message with the udp network protocol
 * @package Mib\Component\Network\UDP
 */
class Socket extends AbstractSocket implements SocketInterface
{
    /**
     * @param bool $createResource
     */
    public function __construct($createResource = false)
    {
        // initialize resource
        if ($createResource)
            $this->init();
    }

    /**
     * internal initialize sequence used for blocking the resources
     * when they are needed
     */
    protected function init()
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (false === $socket) {
            $error = socket_last_error();
            throw new \RuntimeException(sprintf('socket_create failed with: "%s"', socket_strerror($error)));
        }

        $this->resource = $socket;
    }

    /**
     * Sends buffer to the specified address:port
     * Returns the bytes written, size can differ from buffer length if size exceeds tcp buffer size
     * You have to manage remaining data by yourself
     * @param $buffer
     * @param $length
     * @param $addr
     * @param int $port
     * @return int
     */
    public function sendTo($buffer, $length, $addr, $port = 0)
    {
        if (null === $this->resource)
            $this->init();

        $sent = socket_sendto($this->resource, $buffer, $length, 0, $addr, $port);

        if (-1 === $sent) {
            $error = socket_last_error($this->resource);
            throw new \RuntimeException(sprintf('socket_sendto failed with: "%s"', socket_strerror($error)));
        }

        return $sent;
    }

    /**
     * Read data from the socket if there is any
     * This will catch all udp messages that are gathered by the network connection
     * @param int $length
     * @param int $type
     * @return string
     */
    public function read($length = self::BUFFER_SIZE, $type = self::RD_BINARY)
    {
        if (null == $this->resource)
            $this->init();

        $resolvedType = ($type === self::RD_BINARY ? PHP_BINARY_READ : PHP_NORMAL_READ);

        $read = socket_read($this->resource, $length, $resolvedType);

        if (false === $read) {
            $error = socket_last_error($this->resource);
            throw new \RuntimeException(sprintf('socket_read failed with: "%s"', socket_strerror($error)));
        }

        return $read;
    }
}