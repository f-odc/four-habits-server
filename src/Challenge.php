<?php

class Challenge implements JsonSerializable{
    private $id;
    private $habitName;
    private $habitOccurrence;
    private $habitNum;
    private $board;
    private $challengerID;
    private $score;

    public function __construct($id, $name, $occurrence, $num, $board, $challenger, $score) {
        $this->id = $id;
        $this->habitName = $name;
        $this->habitOccurrence = $occurrence;
        $this->habitNum = $num;
        $this->board = $board;
        $this->challengerID = $challenger;
        $this->score = $score;
    }

    public function getId() {
        return $this->id;
    }

    public function getHabitName() {
        return $this->habitName;
    }

    #[ReturnTypeWillChange] public function jsonSerialize(){
        return get_object_vars($this);
    }

    public static function fromJson($json) {
        $data = json_decode($json, true);
        return new self($data['id'], $data['habitName'], $data['habitOccurrence'], $data['habitNum'], $data['board'], $data['challengerID'], $data['score']);
    }


}
?>