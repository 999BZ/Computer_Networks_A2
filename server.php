<?php

$server_ip = "0.0.0.0";
$server_port = 12345;

$admin_ip = "127.0.0.1";

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

socket_bind($socket, $server_ip, $server_port);

echo "Server listening on $server_ip:$server_port\n";

socket_close($socket);
?>
