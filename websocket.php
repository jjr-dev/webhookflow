<?php
    require __DIR__ . '/vendor/autoload.php';

    use Ratchet\MessageComponentInterface;
    use Ratchet\ConnectionInterface;
    use Ratchet\Server\IoServer;
    use Ratchet\Http\HttpServer;
    use Ratchet\WebSocket\WsServer;
    
    use Luna\Utils\Environment;

    use SplObjectStorage;

    Environment::load(__DIR__);

    class WebSocketServer implements MessageComponentInterface {
        protected $clients;

        public function __construct() {
            $this->clients = new SplObjectStorage();
        }

        public function onOpen(ConnectionInterface $conn) {
            $this->clients->attach($conn);
        }

        public function onMessage(ConnectionInterface $from, $msg) {
            foreach($this->clients as $client) {
                if($from !== $client)
                    $client->send($msg);
            }
        }

        public function onClose(ConnectionInterface $conn) {
            $this->clients->detach($conn);
        }

        public function onError(ConnectionInterface $conn, \Exception $e) {
            $conn->close();
        }
    }

    $port = Environment::get('WEBSOCKET_SERVER_PORT');
    if(!$port) $port = 8080;

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new WebSocketServer()
            )
            ),
            $port
        );

    $server->run();