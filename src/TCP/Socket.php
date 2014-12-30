<?php

namespace Mib\Component\Network\TCP;

use Mib\Component\Network\AbstractSocket;
use Mib\Component\Network\SocketInterface;

class Socket extends AbstractSocket implements SocketInterface
{

    private $conntected = false;

    public function __construct($createResource = false)
    {
        if ($createResource)
            $this->init();
    }

    protected function init()
    {
        $resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($resource === false) {
            $error = socket_last_error();
            throw new \RuntimeException(sprintf('socket_create failed with: "%s"', socket_strerror($error)));
        }
        $this->resource = $resource;
    }

    /**
     * Sends the amount of buffer data restricted by the given length to the address
     * If length is 0 the complete buffer size will be used
     * Returns the amount of bytes written
     * This must not match the length provided by $length if the network buffer size is smaller
     * @param mixed $buffer
     * @param int $length
     * @param string $addr
     * @param int $port
     * @return int
     */
    public function sendTo($buffer, $length, $addr, $port = 0)
    {
        // TODO: Implement sendTo() method.
    }

    /**
     * Reads data from the socket
     * Equivalent of recv with flags of 0 same for write and send
     * @param int $length
     * @param int $type
     * @return mixed
     */
    public function read($length = self::BUFFER_SIZE, $type = self::RD_BINARY)
    {
        if (null === $this->resource || false === $this->connected)
            throw new \RuntimeException('unable to read from unconnected socket');

        $resolvedType = ($type === self::RD_BINARY ? PHP_BINARY_READ : PHP_NORMAL_READ);

        $read = socket_read($this->resource, $length, $resolvedType);

        if (false === $read) {
            $error = socket_last_error($this->resource);
            throw new \RuntimeException(sprintf('socket_read failed with: "%s"', socket_strerror($error)));
        }

        return $read;

    }

    public function write($buffer, $length = 0)
    {
        $resource = $this->getResource();

        if (null === $resource || false === $this->connected)
            throw new \RuntimeException('unable to write to unconnected socket');

        $bufferLength = strlen($buffer);
        if (!$length || $length > $bufferLength)
            $length = $bufferLength;

        if (false === ($wrote = @socket_write($resource, $buffer, $length))) {
            $error = socket_last_error($resource);
            throw new \RuntimeException(sprintf('socket_write failed with: "%s"', socket_strerror($error)));
        }

        return $wrote;
    }
}