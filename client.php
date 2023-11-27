<?php

$server_ip = "127.0.0.1";
$server_port = 12345;

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);


socket_close($socket);
?>