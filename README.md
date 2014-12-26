# PHP: mib/network

## Description

This library wraps the core php functions into logical separated classes. At the moment there is just the udp socket
class for sending and receiving messages. More classes will be added.

## Usage

Sending and receiving data with the udp socket:

    $socket = new Mib\Component\Network\UDP\Socket();
    $socket->sendTo('MESSAGE', 7, '127.0.0.1', 12345);
    $result = $socket->read();

