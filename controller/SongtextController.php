<?php
global $parms;

class SongtextController
{
    private $sharp = true;
    private $opentag = "<span class='chord'>";
    private $closetag = "</span>";
    private $chordregex = '/(\b[A-G][#|b]?m?[2-9]?((sus|maj|min|aug|dim|Maj)[2-9]?)?\b)/';
    private $replace;
    private $sharpchord;
    private $flatchord;

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
            require_once './view/Songs/editmenu.php';
        }
    }

    public function __construct(Songtext $songtext)
    {
        $this->songtext = $songtext;

        $this->replace = $this->opentag."$1".$this->closetag;

        $this->sharpchord[$this->opentag."A#"] = $this->opentag."B";
        $this->sharpchord[$this->opentag."C#"] = $this->opentag."D";
        $this->sharpchord[$this->opentag."D#"] = $this->opentag."E";
        $this->sharpchord[$this->opentag."F#"] = $this->opentag."G";
        $this->sharpchord[$this->opentag."G#"] = $this->opentag."A";
        $this->sharpchord[$this->opentag."A"] = $this->opentag."A#";
        $this->sharpchord[$this->opentag."B"] = $this->opentag."C";
        $this->sharpchord[$this->opentag."C"] = $this->opentag."C#";
        $this->sharpchord[$this->opentag."D"] = $this->opentag."D#";
        $this->sharpchord[$this->opentag."E"] = $this->opentag."F";
        $this->sharpchord[$this->opentag."F"] = $this->opentag."F#";
        $this->sharpchord[$this->opentag."G"] = $this->opentag."G#";

        $this->flatchord[$this->opentag."Ab"] = $this->opentag."A";
        $this->flatchord[$this->opentag."A"] = $this->opentag."Bb";
        $this->flatchord[$this->opentag."Bb"] = $this->opentag."B";
        $this->flatchord[$this->opentag."B"] = $this->opentag."C";
        $this->flatchord[$this->opentag."C"] = $this->opentag."Db";
        $this->flatchord[$this->opentag."Db"] = $this->opentag."D";
        $this->flatchord[$this->opentag."D"] = $this->opentag."Eb";
        $this->flatchord[$this->opentag."Eb"] = $this->opentag."E";
        $this->flatchord[$this->opentag."E"] = $this->opentag."F";
        $this->flatchord[$this->opentag."F"] = $this->opentag."Gb";
        $this->flatchord[$this->opentag."Gb"] = $this->opentag."G";
        $this->flatchord[$this->opentag."G"] = $this->opentag."Ab";

        $this->checkFont();
        $this->checkTrans();
        $this->checkColumns();
        $this->text = $this->formatSongtext($this->songtext->getSongtext());

    }

    private function toChords($input){
        return preg_replace($this->chordregex, $this->replace, $input);
    }

    private function transposeUp($input){
        return ($this->sharp) ? strtr($input, $this->sharpchord) : $input = strtr($input, $this->flatchord);
    }

    private function transposeDown($input){
        return ($this->sharp) ? strtr($input, array_flip($this->sharpchord)) : $input = strtr($input, array_flip($this->flatchord));
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