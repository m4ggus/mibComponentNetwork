<?php
/**
 * Created by PhpStorm.
 * User: marcus
 * Date: 12/24/14
 * Time: 2:26 AM
 */

namespace Mib\Component\Network;


abstract class AbstractSocket {

    protected $resource;

    public function close()
    {
        if (null !== $this->resource) {
            @socket_close($this->resource);
            $this->resource = null;
        }
    }

    public function getResource()
    {
        return $this->resource;
    }

}