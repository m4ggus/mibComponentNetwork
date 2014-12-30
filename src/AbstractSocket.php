<?php
/**
 * Created by PhpStorm.
 * User: marcus
 * Date: 12/24/14
 * Time: 2:26 AM
 */

namespace Mib\Component\Network;


abstract class AbstractSocket {

    protected $connected;

    /** @var resource|null */
    protected $resource;

    abstract protected function init();

    public function connect($addr, $port, $async = false)
    {
        if (null === $this->resource)
            $this->init();

        if (false === @socket_connect($this->resource, $addr, $port)) {

            $error = socket_last_error($this->resource);

            // throw runtime exception on synced connect or if the error is not operation in progress
            if (!$async || 115 !== $error) {
                $this->close();
                throw new \RuntimeException(sprintf('socket_connect failed with: "%s"', socket_strerror($error)));
            }
        }

        $this->connected = true;
    }

    public function close()
    {
        if (null !== $this->resource) {
            @socket_close($this->resource);
            $this->resource = null;
        }

        $this->connected = false;
    }

    public function getResource()
    {
        return $this->resource;
    }

}