<?php

$server_ip = "0.0.0.0";
$server_port = 12345;

class Client{
    public $alias;
    public $ip;
    public function __construct($ip){
        $this->ip = $ip;
    }
}

$clients = array();

$admin_ip = "127.0.0.1";

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if($socket===false){
    echo "Something went wrong, please try again!";
    exit;
}
socket_bind($socket, $server_ip, $server_port);

echo "Server listening on $server_ip:$server_port\n";

while (true) {
    socket_recvfrom($socket, $data, 1024, 0, $client_ip, $client_port);
    
    echo "Received data from $client_ip:$data\n";

    if(!checkClient($client_ip)){
        addClient(new Client($client_ip));
    }

    $response = processRequest($data, $client_ip);

    socket_sendto($socket, $response, strlen($response), 0, $client_ip, $client_port);
}

socket_close($socket);

function processRequest($request, $client_ip)
{
    global $admin_ip;
    if(substr($request, 0, 1) == "/" ) {
        switch($request) {
            case "/":
                $response = "Command format is: /{Command}. Type /help for the list of commands!";
                break;
            case "/help":
                if($client_ip == $admin_ip){
                    $response = "Available commands are:<br>/files<br>/create<br>/exec<br>/delete<br>/clients<br>/disconnect";
                } else {
                    $response = "Available commands are:<br>/files<br>/clients<br>/disconnect";
                }
               
                break;
            case "/files":
                    $files = is_dir(__DIR__."/files") ? scandir(__DIR__."/files") : [];
                    $response = "Files on server: " . ($files ? implode(", ", $files) : "Directory empty or not found");
                break;
            case "/clients":
                $response = printClients();
                break;
            case substr($request, 0, 5) == "/exec":
                $file = substr($request,6);
                if($client_ip==$admin_ip){
                    if($file == "") {
                        $response = "Please specify the file: /exec {File Name}!";
                    }else {
                        $fileDirectory = __DIR__."/files"."/".$file;
                        if (file_exists($fileDirectory)) {
                            $fullPath = realpath($fileDirectory);
                            exec ("start \"\" \"$fullPath\"", $output,$returnCode);
                            if($returnCode === 0){
                                $response = "The file has been successfully executed!";
                            }else {
                                $response = "There was an error while executing the file! Please try again.";
                            }
                        }else{
                            $response = "The specified file does not exist!";
                        }
    
                    }
                    
                } else {
                    $response = "You do not have permission to use this command!";
                }

                
                break;
            case substr($request, 0, 7) == "/create":
                $type = substr($request, 8,4);
                if($client_ip == $admin_ip){
                    if(substr($request,8)!=""){
                        $fileName = substr($request, 13);
                        if($type == "file"){
                            if($fileName != ""){
                                $fileDirectory = __DIR__."/files"."/".$fileName.".txt";
                               
                                if(file_exists($fileDirectory)) {
                                    $response = "The file already exists.Please choose a different name!";
                                }else{
                                    fopen($fileDirectory, "w") or die("Unable to open file!");
                                    $response = "/ttf".$fileDirectory;
                                }
                            }else{
                                $response = "Please specify the name of the file you want to create!";
                            }
                        }else if($type == "fold"){
                            if($fileName != ""){
                                $folderDirectory = __DIR__."/files"."/".$fileName;
                                if(file_exists($folderDirectory) && is_dir($folderDirectory)) {
                                    $response = "The folder already exists.Please choose a different name!";
                                }else{
                                    mkdir($folderDirectory, 0777, true);
                                    $response = "The folder has been successfully created!";
                                }
                            }else{
                                $response = "Please specify the name of the folder you want to create!";
                            }
                        }
                    }else {
                        $response = "Full command is: /create {file or fold} {File Name}";
                    }
                }else{
                    $response = "You do not have permission to use this command!";
                }
                
                break;
            case substr($request,0,4) == "/ttf":
                if($client_ip == $admin_ip){
                    $fileName = substr($request, 4,strpos($request, ',')-4);
                    $message = substr(strstr($request, ","), 1);
                    $myfile = fopen($fileName, "w") or die("Unable to open file!");
                    fwrite($myfile, $message);
                    fclose($myfile);
                    $response = "File has been created!";
                } else {
                    $response = "You do not have permission to use this command!";
                }
                break;
            case substr($request,0,7) == "/delete":
               
                if($client_ip == $admin_ip) {

                    if(substr($request, 8)!= "") {
                        $file = __DIR__."/"."files/".substr($request,8);
                        if(file_exists($file)){
                            unlink($file);
                            $response = "The file has been successfully removed!";
                        }else{
                            $response = "The file you are trying to delete doesn't exist!";
                        }
                    } else {
                        $response = "Full command is: /delete {File Name}";
                    }
                }else {
                    $response = "You do not have permission to use this command!";
                }
                
                break;
            case "/disconnect":
                $response = "Goodbye!";
                removeClient(new Client($client_ip));
                break;
            default:
                $response = "Invalid command!";
                break;
        }
    }else{
        $response = "Message received from the server!";
    }
    return $response;
}

function checkClient($c_ip){
    global $clients;
    $ClientInList = false;
    for($i = 0; $i < sizeof($clients); $i++){
        if ($clients[$i]->ip == $c_ip){
            $ClientInList = true;
        }
    }

    return $ClientInList;
}

function addClient(Client $client){
    global $clients;
    $client->alias = "User ".rand(1000000000,9999999999);
    array_push( $clients, $client );
}

function removeClient(Client $client) {
    global $clients;

    foreach ($clients as $key => $existingClient) {
        if ($existingClient->ip == $client->ip) {
            unset($clients[$key]);
        }
    }
    $clients = array_values($clients);
}

function printClients(){
    global $clients;
    $response = "Clients connected: ";
    for( $i = 0; $i < sizeof($clients); $i++){
        $response = $response."<br>".$clients[$i]->alias." (".$clients[$i]->ip.")";
    }
    return $response;
}


?>
