<?php
/**
 * Created by PhpStorm.
 * User: marcus
 * Date: 12/24/14
 * Time: 2:18 AM
 */
namespace Mib\Component\Network;

/**
 * Interface SocketInterface
 * @package Mib\Component\Network
 */
interface SocketInterface
{
    const RD_BINARY = 0;
    const RD_TEXT   = 1;

    const BUFFER_SIZE = 4096;

    /**
     * Close the socket
     * Releases the used resource and make it available for reuse
     */
    public function close();

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
    public function sendTo($buffer, $length = 0, $addr, $port = 0);

    /**
     * Reads data from the socket
     * @param int $length
     * @param int $type
     * @return mixed
     */
    public function read($length = self::BUFFER_SIZE, $type = self::RD_BINARY);

    /**
     * Returns the underlying socket resource
     * @return mixed
     */
    public function getResource();
}