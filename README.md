# Installationsanleitung

## Vorbereitung

Der Code der Webapplikation befindet sich auf GitHub unter folgendem Link: https://github.com/jxxmin/lyricsweb. In einem ersten Schritt muss der komplette Code heruntergeladen werden. Danach soll XAMPP geöffnet und MySQL gestartet werden.

## Datenbank mit Replikation einrichten
Um die Datenbank einzurichten, muss zuerst eine Master-Slave-Verbindung zur Replikation eingerichtet werden. Dazu werden mind. zwei Server benötigt, die sich im selben Netz befinden. Auf beiden soll MariaDB installiert sein. Um die Replikation einzurichten, sind folgende Schritte nötig:
Auf dem Master:
1)	Datenbank lokal einrichten (DDL namens «lyricsweb_ddl.sql» importieren)
2)	Im my.cnf / my.ini-File binary logging aktivieren, indem die log-bin Option aktiviert wird (nicht mehr auskommentiert ist)
3)	In my.cnf / my.ini-File unter server-id eine einzigartige Server ID festlegen (Integer zwischen 1 und (232)-1)
4)	User erstellen, der REPLICATION SLAVE – Privilegien hat
5)	Server restarten
Auf dem Slave / den Slaves:
1)	Datenbank lokal einrichten (DDL namens «lyricsweb_ddl.sql» importieren)
2)	In my.cnf / my.ini-File unter server-id eine einzigartige Server ID festlegen (Integer zwischen 1 und (232)-1)
3)	Server restarten
4)	Zu Master User connecten (CHANGE MASTER TO)
5)	Tabellen löschen und write-Statements unterbinden (FLUSH TABLES WITH READ LOCK)
6)	Binary log position herausfinden und eintragen, findet man mit SHOW MASTER STATUS auf dem Master heraus
Danach können die Daten (DML namens «lyricsweb_dml.sql») in die Master-Datenbank importiert werden. Der Client übernimmt die Änderungen automatisch.

## Webapplikation starten
Nachdem die Datenbank aufgesetzt ist, sind noch wenige Schritte nötig, um die Webapplikation zu starten.
1)	Apache in XAMPP starten
2)	Alle Files, die von GitHub heruntergeladen wurden, in den htdocs-Ordner von XAMPP kopieren
Nun kann in einem Browser der Wahl die URL localhost/lyricsweb aufgerufen werden.
 
