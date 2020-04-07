<?php


class DBAccess
{
    private static $dbAccess;

    //Connection
    private $connection;
    private const user = 'lyrics_guest';
    private const password = 'guest'; //TODO
    private const adminUser = 'lyrics_admin';
    private const adminPassword = '243-ASF-6ZT-009'; //TODO
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
            $this->connection = (LoginController::isAdmin())
                ? new mysqli($this::host,$this::adminUser,$this::adminPassword,$this::database)
                : new mysqli($this::host,$this::user,$this::password,$this::database);
            return $this->connection;
        }
        catch(PDOException $e){
            /*
            echo '<p>Verbindung fehlgeschlagen';
            if(ini_get('display_errors')){
                echo $e -> getMessage();
            }*/
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
        $query = "CALL `spInsertUser`(?, ?);";
        $return = false;
        try {
            if($stmt = mysqli_prepare($this->getConn(), $query)){
                $name = $user->getUsername();
                $pw = $user->getHashedPassword();
                mysqli_stmt_bind_param($stmt, "ss", $name, $pw);
                $return = $stmt->execute();
                mysqli_stmt_close($stmt);
                return $return;
            } else{
                echo mysqli_error($this->connection);
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
        return $return;
    }

    public function updateSongtext(Songtext $songtext){
        $query = "CALL `spUpdateSong`(?, ?, ?);";
        try {
            if($stmt = mysqli_prepare($this->getConn(), $query)){
                $id = $songtext->getId();
                $title = $songtext->getTitle();
                $text = $songtext->getSongtext();
                mysqli_stmt_bind_param($stmt, "iss", $id, $title, $text);
                $return = $stmt->execute();
                mysqli_stmt_close($stmt);
                return $return;
            } else{
                echo mysqli_error($this->connection);
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }
    public function addSongtext(Songtext $songtext){
        $query = "CALL `spAddSong`(?, ?, ?);";
        try {
            if($stmt = mysqli_prepare($this->getConn(), $query)){
                $title =  $songtext->getTitle();
                $text = $songtext->getSongtext();
                $genre = $songtext->getGenre()->getId();
                mysqli_stmt_bind_param($stmt, "sss", $title, $text, $genre);
                $return = $stmt->execute();
                mysqli_stmt_close($stmt);
                return $return;
            } else{
                //echo mysqli_error($this->connection);
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }
    public function addGenre(Genre $genre){
        $query = "CALL `spAddGenre`(?);";
        try {
            if($stmt = mysqli_prepare($this->getConn(), $query)){
                $g = $genre->getGenre();
                mysqli_stmt_bind_param($stmt, "s",  $g);
                $return = $stmt->execute();
                echo mysqli_error($this->connection);
                mysqli_stmt_close($stmt);
                return $return;
            } else{
                echo mysqli_error($this->connection);
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }
    public function deleteSong(Songtext $songtext){
        $query = "CALL `spDeleteSong`(?);";
        try {
            if($stmt = mysqli_prepare($this->getConn(), $query)){
                $id = $songtext->getId();
                mysqli_stmt_bind_param($stmt, "i",  $id);
                $return = $stmt->execute();
                mysqli_stmt_close($stmt);
                return $return;
            } else{
                echo mysqli_error($this->connection);
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
    }
    public function deleteGenre(Genre $genre){
        $query = "CALL `spDeleteGenre`(?);";
        try {
            if($stmt = mysqli_prepare($this->getConn(), $query)){
                $id = $genre->getId();
                mysqli_stmt_bind_param($stmt, "i",  $id);
                $return = $stmt->execute();
                mysqli_stmt_close($stmt);
                return $return;
            } else{
                echo mysqli_error($this->connection);
            }
        } catch (Exception $e) {
            echo "Query konnte nicht ausgeführt werden";
            return false;
        }
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