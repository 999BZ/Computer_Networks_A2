<?php
        $server_ip = "127.0.0.1";
        $server_port = 12345;

        $creatingFile = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

            $message = explode("|",$_POST['mesazhi'])[0];
            $creatingFile = explode("|",$_POST['mesazhi'])[1];

            if ($creatingFile != "") {
                $message = $creatingFile . "," . $message;
                $creatingFile = "";
            }

            socket_sendto($socket, $message, strlen($message), 0, $server_ip, $server_port);

            socket_recvfrom($socket, $response, 1024, 0, $server_ip, $server_port);

            switch ($response) {
                case substr($response, 0, 4) == "/ttf":
                    $creatingFile = $response;
                    $response = "Enter text for the file: ";
                    break;
            }

            echo $response."|". $creatingFile;
            socket_close($socket);
            exit;
        }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client</title>
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Avatar_icon_green.svg/240px-Avatar_icon_green.svg.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        #chatContainer {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #messages {
            height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        #messageInput {
            width: 70%;
            padding: 8px;
        }

        #sendButton {
            width: 20%;
            padding: 8px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #sendButton:hover {
            background-color: #45a049;
        }
        #messages::-webkit-scrollbar {
            width: 5px;
        }

        #messages::-webkit-scrollbar-thumb {
            background-color: #4caf50;
            border-radius: 2px;
        }

        #messages::-webkit-scrollbar-thumb:hover {
            background-color: #45a049;
        }

        #messages::-webkit-scrollbar-track {
            background-color: white;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div id="chatContainer">
        <div id="messages"></div>
        <form id="messageForm">
            <label for="messageInput">Type your message:</label>
            <input type="text" name="mesazhi" id="messageInput" autocomplete="off">
            <button type="submit" name="dergoni" id="sendButton">Send</button>
        </form>
    </div>


    <script>
        $(document).ready(function () {
            var creatingFiles = "";
            function sendMessage(message) {
                $.ajax({
                    type: "POST",
                    url: "client.php",
                    data: { mesazhi: message +"|"+ creatingFiles},
                    success: function (response) {
                        var responses = response.split('|');
                        var messagesContainer = $("#messages");
                        messagesContainer.append("Me: " +message + "<br>Server: " + responses[0]+ "</br>");
                        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
                        $("#messageInput").val("");
                        creatingFiles =responses[1];
                    },
                    error: function (error) {
                        console.error("Error:", error);
                    }
                });
            }

            $("#messageForm").submit(function (event) {
                event.preventDefault();
                var message = $("#messageInput").val();
                if(message != ""){
                    sendMessage(message);
                }
                
            });
        });
    </script>
</body>
</html>