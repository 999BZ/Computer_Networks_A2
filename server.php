<?php

$server_ip = "0.0.0.0";
$server_port = 12345;

$admin_ip = "127.0.0.1";

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

socket_bind($socket, $server_ip, $server_port);

echo "Server listening on $server_ip:$server_port\n";
while (true) {
    socket_recvfrom($socket, $data, 1024, 0, $client_ip, $client_port);

    $pid = pcntl_fork();
    echo "Received data from $client_ip:$client_port: $data\n";

    if(!checkClient($client_ip)){
        addClient(new Client($client_ip, $client_port));
    }

    $response = processRequest($data, $client_ip);

    socket_sendto($socket, $response, strlen($response), 0, $client_ip, $client_port);
}

socket_close($socket);
?>
