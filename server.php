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


    $response = processRequest($data, $client_ip);

    socket_sendto($socket, $response, strlen($response), 0, $client_ip, $client_port);
}

function processRequest($request, $client_ip)
{
    global $admin_ip;
    if (substr($request, 0, 1) == "/") {
        if ($request == "/") {
            $response = "Command format is: /{Command}. Type /help for the list of commands!";
        } else if ($request == "/files") {
            if ($client_ip == $admin_ip) {
                $files = scandir(__DIR__);
                $response = "Files on server: " . implode(", ", $files);
            } else {
                $response = "You don't have permission for this command!";
            }
        } else if ($request == "/help") {
            $response = "Available commands are:\n/files\n/disconnect";
        }else if($request=="/disconnect") {
            $response = "Good bye!";
        }else if($request == "/clients"){
            $response = printClients();
        }else if(substr($request, 0, 5) == "/exec"){
            $file = substr($request,6);
            $fileDirectory = __DIR__."/".$file;
            if (file_exists($fileDirectory)) {
                exec ($file, $output,$returnCode);
                if($returnCode === 0){
                    $response = "The file has been successfully executed!";
                }else {
                    $reponse = "There was an error while executing the file! Please try again.";
                }
            }else{
                 $response = "The specified file does not exist!";
            }
            
            
        }
         else {
            $response = "Invalid command!";
        }
    } else {
        $response = "Message received from the server!";
    }

    return $response;
}

socket_close($socket);
?>
