<?php


class DBAccess
{
    private static $dbAccess;

    //Connection
    private $adminConnection;
    private $connection;
    private const user = 'root';
    private const password = '';
    private const adminUser = 'root'; //TODO
    private const adminPassword = ''; //TODO
    private const host = 'localhost';
    private const database = 'lyricsweb';

    public static function getController(){
        if(is_null(self::$dbAccess) ){
            self::$dbAccess = new DBAccess();
        }
        return self::$dbAccess;
    }

    public function getConnection(){
        return $this->getConn();
    }

    private function getConn(){
        try {
            $this->connection = (LoginController::isAdmin()) ? new mysqli($this::host,$this::adminUser,$this::adminPassword,$this::database) : new mysqli($this::host,$this::user,$this::password,$this::database);
            return $this->connection;
        }
        catch(PDOException $e){
            echo '<p>Verbindung fehlgeschlagen';
            if(ini_get('display_errors')){
                echo $e -> getMessage();
            }
            exit;
        }
    }


    public function getSongtext(Genre $genre, $songId){
        $query = "select * from lyrics where id = ".$songId." and fk_genre = ".$genre->getId() ;
        $return = false;
        try {
            $result = mysqli_query($this->getConn(), $query);
            if (!is_null($result)){
                $row = mysqli_fetch_array($result);
                $return = new Songtext($row['id'],$row['songtext'],$row['titel'],$genre);
            }

        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
        }
        return $return;
    }

    public function getGenre($id){
        $query = "select * from genre where id = ".$id ;
        $return = false;
        try {
            $result = mysqli_query($this->getConn(), $query);
            if (!is_null($result)){
                $row = mysqli_fetch_array($result);
                $genre = new Genre($row['id'],$row['genre']);
                $genre->setSongs($this->getAllSongs($genre));
                $return = $genre;
            }

        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
        }
        return $return;
    }

    public function getUser($username){
        $query = "select * from `login` where username like '$username'";
        $return = false;
        try {
            $result = mysqli_query($this->getConn(), $query);
            if ($result){
                $row = mysqli_fetch_array($result);
                $return = new User($row['username'],$row['password'], $row['admin']);
            }

        } catch (Exception $e) {
            var_dump($e);
            echo "Query konnte nicht ausgeführt werden";
        }
        return $return;
    }

    public function insertUser(User $user){
        $query = "insert into login (username, password, admin) values  (?, ?, ?);";
        $return = false;
        try {
            if ($stmt = mysqli_prepare($this->getConn(), $query)) {
                $username = $user->getUsername();
                $hashedPassword = $user->getHashedPassword();
                $isAdmin = $user->isAdmin();
                mysqli_stmt_bind_param($stmt, "ssb", $username, $hashedPassword, $isAdmin);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $return = true;
            }

        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
        return $return;
    }

    public function updateSongtext(Songtext $songtext){
        return true;
    }

    public function loadAllGenres(){
        $query = "select * from genre";
        $genres = false;
        try {
            $result = mysqli_query($this->getConn(), $query);
            if (!is_null($result)){
                $genres = [];
                while($row = mysqli_fetch_array($result)){
                    $genre = new Genre($row['id'],$row['genre']);
                    $genre->setSongs($this->getAllSongs($genre));
                    $genres[] = $genre;
                }
            }

        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
        }
        return $genres;
    }


    private function getAllSongs(Genre $genre){
        $query = "select * from lyrics where fk_genre = ".$genre->getId() ;
        $songs = false;
        try {
            $result = mysqli_query($this->getConn(), $query);
            if (!is_null($result)){
                $songs = [];
                while($row = mysqli_fetch_array($result)){
                    $songs[] = new Songtext($row['id'],$row['songtext'],$row['titel'],$genre);
                }
            }

        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
        }
        return $songs;
    }


}