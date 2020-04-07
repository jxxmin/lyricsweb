<?php
global $parms;

class SongtextController
{
    private $sharp = true;
    private const opentag = "<span class='chord'>";
    private const closetag = "</span>";
    private const chordregex = '/(\b[A-G][#|b]?m?[2-9]?((sus|maj|min|aug|dim|Maj)[2-9]?)?\b)/';
    private const replace = self::opentag."$1".self::closetag;
    private const sharpchord = array(
        self::opentag."A#" => self::opentag."B",
        self::opentag."C#" => self::opentag."D",
        self::opentag."D#" => self::opentag."E",
        self::opentag."F#" => self::opentag."G",
        self::opentag."G#" => self::opentag."A",
        self::opentag."A" => self::opentag."A#",
        self::opentag."B" => self::opentag."C",
        self::opentag."C" => self::opentag."C#",
        self::opentag."D" => self::opentag."D#",
        self::opentag."E" => self::opentag."F",
        self::opentag."F" => self::opentag."F#",
        self::opentag."G" => self::opentag."G#"
    );
    private const flatchord = array(
        self::opentag."Ab" => self::opentag."A",
        self::opentag."A" => self::opentag."Bb",
        self::opentag."Bb" => self::opentag."B",
        self::opentag."B" => self::opentag."C",
        self::opentag."C" => self::opentag."Db",
        self::opentag."Db"=> self::opentag."D",
        self::opentag."D" => self::opentag."Eb",
        self::opentag."Eb"=> self::opentag."E",
        self::opentag."E" => self::opentag."F",
        self::opentag."F" => self::opentag."Gb",
        self::opentag."Gb"=> self::opentag."G",
        self::opentag."G" => self::opentag."Ab"
    );

    private $trans;
    private $col;
    private $fontSize = 15;
    private $fontScale;
    private $style;
    private $columnStyle;

    private $songtext;

    private $text;

    public function render($includeEditButtons){
        $title = $this->songtext->getTitle();
        $songtext = $this->text;
        $columns = $this->columnStyle;

        //PARMS: title, columns, songtext
        require_once './view/Songs/songtext.php';

        $genre = $this->songtext->getGenre()->getId();
        $song = $this->songtext->getId();
        $font = $this->fontScale;
        $trans = $this->trans;
        $col = $this->col;

        //parms: $genre, $song, $font, $trans, $col
        require_once './view/Songs/transposemenu.php';

        if($includeEditButtons){
            $token = LoginController::getToken();
            //parms: $genre, $song, $token,
            require_once './view/Songs/editmenu.php';
        }
    }

    public function __construct(Songtext $songtext)
    {
        $this->songtext = $songtext;
        $this->checkFont();
        $this->checkColumns();
        $this->text = $this->formatSongtext($this->songtext->getSongtext());
        $this->checkTrans();

    }

    private function toChords($input){
        return preg_replace(self::chordregex, self::replace, $input);
    }

    private function transposeUp($input){
        return ($this->sharp) ? strtr($input, self::sharpchord) : $input = strtr($input, self::flatchord);
    }

    private function transposeDown($input){
        return ($this->sharp) ? strtr($input, array_flip(self::sharpchord)) : $input = strtr($input, array_flip(self::flatchord));
    }

    private function checkFont(){
        if(isset($_POST['font'])){
            $this->fontScale = intval($_POST['font']);
            if($this->fontScale<-4){
                $this->fontScale=-4;
            }
            if($this->fontScale>5){
                $this->fontScale=5;
            }
            if($this->fontScale>0){
                for($i=0;$i<$this->fontScale;$i++){
                    $this->fontSize = ($this->fontSize + 2);
                }
            }
            elseif($this->fontScale<0){
                for($i=0;$i>$this->fontScale;$i--){
                    $this->fontSize = ($this->fontSize - 2);
                }
            }
        }
        $this->style= "style='line-height:1;font-size: ".$this->fontSize."px;'";
    }

    private function formatSongtext($text){
        $songtext = "";
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $line){
            if(empty($line) or ctype_space($line)){
                $line = "<p class='song'><br>";
            } else {
                $line = "<p class='song'>".$this->tochords($line)."<br>";
            }
            $songtext .= $line;
        }
        $search = "<p class='song'>";
        $replace = "<p class='song' $this->style>";
        return str_replace($search, $replace, $songtext);
    }


    private function checkTrans(){
        if(isset($_POST['trans'])){
            $this->trans = intval($_POST['trans']);
            switch(true){
                case $this->trans==12:
                case $this->trans==-12:
                    $this->trans=0;
                    break;
                case $this->trans>0:
                    for($i=0;$i<$this->trans;$i++){
                        $this->text = $this->transposeUp($this->text);
                    }
                    break;
                case $this->trans<0:
                    for($i=0;$i>$this->trans;$i--){
                        $this->text = $this->transposeDown($this->text);
                    }
                    break;
            }
        }
    }


    private function checkColumns(){
        if (isset($_POST['col'])) {
            $this->col = intval($_POST['col']);
            $this->columnStyle = "";
            if ($this->col > 3) {
                $this->col = 4;
            }
            if ($this->col < 2) {
                $this->col = 1;
            }
            if ($this->col > 1) {
                $this->columnStyle = "style='columns:$this->col'";
            }
        }
    }



}