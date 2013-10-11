[FR]

  Installation

1. Déposer le fichier remote.php sur le serveur distant.
2. Faire une COPIE (ne pas le rennomer) du fichier serverInfo.php.dist que vous appelerais serverInfo.php
3. Editer le fichier serverInfo.php avec les données relatives au serveur distant

  Utilisation

1. Inclure le fichier remotePDO.php
2. Instancier de cette manière: new remotePDO(<instance>, <username>, <password>);
3. Utiliser de la même manière que PDO (toutes les fonctionnalités de PDO ne sont pas encore implémenté)