# Computer_Networks_A2

P2. Programming with Sockets - Grupi 6 - UDP Protocol - PHP
Komunikim me Sockets në PHP me Protokollin UDP
Për të mundësuar komunikim efektiv mes një serveri dhe klientave, kemi përdorur sockets. Klientët janë të ndarë në dy role: admin dhe normal.

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
Serveri është i përgatitur të përgjigjet ndaj kërkesave të secilit klient. Kjo është një implementim i thjeshtë i komunikimit me sockets duke përdorur protokollin UDP në PHP. Mund të modifikohet dhe përdoret për nevoja specifike të aplikacioneve.
