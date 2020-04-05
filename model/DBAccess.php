<?php


class DBAccess
{
    private static $dbAccess;

    //Connection
    private $connection;
    private const user = 'root';
    private const password = '';
    private const host = 'localhost';
    private const database = 'lyricsweb';

    private function __construct(){
        $this->openConnection();
    }

    public static function getController(){
        if(is_null(self::$dbAccess)){
            self::$dbAccess = new DBAccess();
        }
        return self::$dbAccess;
    }

    public function getConnection(){
        return $this->connection;
    }

    public function getSongtext(Genre $genre, $songId){
        $query = "select * from lyrics where id = ".$songId." and fk_genre = ".$genre->getId() ;
        try {
            $result = mysqli_query($this->connection, $query);
            if (!is_null($result)){
                $row = mysqli_fetch_array($result);
                return new Songtext($row['id'],$row['songtext'],$row['titel'],$genre);
            } else return false;
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }

    public function getGenre($id){
        $query = "select * from genre where id = ".$id ;
        try {
            $result = mysqli_query($this->connection, $query);
            if (!is_null($result)){
                $row = mysqli_fetch_array($result);
                $genre = new Genre($row['id'],$row['genre']);
                $genre->setSongs($this->getAllSongs($genre));
                return $genre;
            } else return false;
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }

    public function getUser($username){
        $query = "select * from `login` where username like '$username'";
        try {
            $result = mysqli_query($this->connection, $query);
            if ($result){
                $row = mysqli_fetch_array($result);
                return new User($row['username'],$row['password'], $row['admin']);
            } else return false;
        } catch (Exception $e) {
            var_dump($e);
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }

    public function insertUser(User $user){
        $query = "insert into login (username, password, admin) values  (?, ?, ?);";
        try {
            if ($stmt = mysqli_prepare($this->connection, $query)) {
                $username = $user->getUsername();
                $hashedPassword = $user->getHashedPassword();
                $isAdmin = $user->isAdmin();
                mysqli_stmt_bind_param($stmt, "ssb", $username, $hashedPassword, $isAdmin);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                return true;
            } else return false;
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }

    public function updateSongtext(Songtext $songtext){

    }

    private function openConnection(){
        try {
            $this->connection = new mysqli($this::host,$this::user,$this::password,$this::database);
        }
        catch(PDOException $e){
            echo '<p>Verbindung fehlgeschlagen';
            if(ini_get('display_errors')){
                echo $e -> getMessage();
            }
            exit;
        }
    }

    public function loadAllGenres(){
        $query = "select * from genre";
        try {
            $result = mysqli_query($this->connection, $query);
            if (!is_null($result)){
                $genres = [];
                while($row = mysqli_fetch_array($result)){
                    $genre = new Genre($row['id'],$row['genre']);
                    $genre->setSongs($this->getAllSongs($genre));
                    $genres[] = $genre;
                }
                return $genres;
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }


    private function getAllSongs(Genre $genre){
        $query = "select * from lyrics where fk_genre = ".$genre->getId() ;
        try {
            $result = mysqli_query($this->connection, $query);
            if (!is_null($result)){
                $songs = [];
                while($row = mysqli_fetch_array($result)){
                    $songs[] = new Songtext($row['id'],$row['songtext'],$row['titel'],$genre);
                }
                return $songs;
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }


}