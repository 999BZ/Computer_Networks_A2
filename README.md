# Computer_Networks_A2

Për realizimin e Komuninkimit me Sockets në PHP duke përdorur protokollin UDP kemi krijuar dy file: server.php dhe client.php.

Server.php

$server_ip dhe $server_port përcaktojnë IP-në dhe portin ku serveri dëgjon.
Client është një klasë e krijuar për paraqitjen e klientëve, me një anëtar alias dhe një anëtar ip.
admin_ip përcakton IP-në e klientit(admin), që ka akses të veçantë për disa komanda.
Krijohet një socket UDP dhe dëgjon në IP adresën dhe portin e caktuar.
Pritet për të dhëna nga klientët dhe përgjigjet pas procesimit.

Funksionet Kryesore:

processRequest(): Proceson dhe kthen një përgjigje për kërkesën e pranuar.
checkClient(): Verifikon nëse klienti është në listën e klientëve të lidhur.
addClient(): Shton një klient të ri në listën e klientëve.
removeClient(): Largohet një klient nga lista e klientëve.
printClients(): Kthen një string që përmban informacionin e klientëve të lidhur.


Klient.php

$server_ip dhe $server_port përcaktojnë IP-në dhe portin ku serveri dëgjon.

Dërgimi i Kërkesës dhe Përgjigja nga Serveri:

Për të komunikuar me serverin, krijohet një socket me socket_create e cila dërgon dhe pranon mesazhe përmes socket_sendto dhe socket_recvfrom.
Nëpërmjet një kërkese POST, klienti dërgon një mesazh tek serveri.
Pasi dërgohet, pritet përgjigja nga serveri dhe përpunohet sipas rastit.
Për manipulimin e ngjarjeve dhe komunikimin me serverin, është përdorur jQuery.
Është përdorur një funksion sendMessage për të dërguar mesazhet në server përmes një kërkese AJAX.


Roli i Adminit:
Permissions: Read, Write, Execute.
Komandat e Mundshme:
/files: Lexo listën e skedarëve në server.
/create: Krijo skedar ose dosje sipas nevojës.
... dhe komanda të tjera sipas nevojës.

Roli Normal:
Permissions: Vetëm Read.
Komanda e Mundshme:
/files: Lexo informacionin mbi skedarët në server.


Mënyra e ekzekutimit:

Hapet Command Prompt dhe shkruhet komanda:
php -f path to htdocs\server.php {alt: Hapet terminali në Visual Studio Code dhe shkruhet komanda: php server.php}
Startohet XAMPP dhe pastaj në browser shkruhet komanda:
localhost/ path to client.php


Grupi 6
Anëtarët: Besmira Berisha, Blendi Zeqiri, Bleron Morina dhe Blerona Jashanica
Lënda: Rrjetat Kompjuterike
Profesor i lëndës: Blerim Rexha
Assistent: Mërgim Hoti
