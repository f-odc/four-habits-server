<?php

class Challenge implements JsonSerializable{
    private $id;
    private $challenger;
    private $challengerID;
    private $lastMoverID;
    private $board;
    private $canPerformMove;
    private $habitId;
    private $habitName;
    private $habitOccurrenceType;
    private $habitOccurrenceNum;

    public function __construct($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function isValid(): bool
    {
        return isset($this->id, $this->challenger, $this->challengerID, $this->lastMoverID, $this->board, $this->canPerformMove, $this->habitId, $this->habitName, $this->habitOccurrenceType, $this->habitOccurrenceNum);
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

    public static function fromJson($json): Challenge
    {
        $data = json_decode($json, true);
        return new self($data);
    }

}
?>