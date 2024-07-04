<?php

declare(strict_types=1);
date_default_timezone_set("Asia/Tashkent");
class Pwot{

    public $pdo;
    public $data;
    public int $work_time_in_min = 540;
    public $arrived_at;
    public $leaved_at;
    public $debt;
    public int $total_work_off = 0;

    public function __construct(){
        $this->pdo = new PDO('mysql:host=localhost;dbname=work_off_tracker',
            'root',
            '1234');
        $this->data = $this->pdo->query('SELECT * FROM daily')->fetchAll();
        $this->total_work_off += $this->total_work_off();

    }

    public function insert(): void
    {

        $this->arrived_at = new DateTime($_POST['arrived_at']);
        $this->leaved_at =  new DateTime($_POST['leaved_at']);

        $query = "INSERT INTO daily (arrived_at, leaved_at, required_work_off, worked_off)
                      VALUES (:arrived_at, :leaved_at, :required_work_off, :worked_off)";

        $arrived_at = $this->arrived_at->format('Y-m-d H:i:s');
        $leaved_at = $this->leaved_at->format('Y-m-d H:i:s');

        $required_work_off = $this->calculate_required_work();
        $value = $required_work_off == 0 || $required_work_off < 0 ? 1:0;

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':arrived_at', $arrived_at);
        $stmt->bindParam(':leaved_at', $leaved_at);
        $stmt->bindParam(':required_work_off', $required_work_off);
        $stmt->bindParam(':worked_off', $value);
        $stmt->execute();
    }

    public function get_total_work_off(){
        return $this->total_work_off;
    }

    public function calculate_required_work(){
        $interval = $this->arrived_at->diff($this->leaved_at);
        $worked_off = $interval->h * 60 + $interval->i;
        return $this->work_time_in_min - $worked_off;
    }

    public function fetch_total_work_off(): false|array
    {
        $query = "SELECT required_work_off FROM daily WHERE worked_off = 0";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $this->debt = $stmt->fetchAll();
        return $this->debt;
    }

    public function total_work_off(){
        $total_work_off = 0;
        $this->debt = $this->fetch_total_work_off();
        foreach ($this->debt as $data) {
            if (isset($data['required_work_off'])) {
                $total_work_off += $data['required_work_off'];
            }
        }
        return $total_work_off;
    }
}

$pwot = new Pwot();
if (!empty($_POST)) {
    if (isset($_POST['arrived_at']) && isset($_POST['leaved_at'])) {
        if ($_POST['arrived_at'] !== '' && $_POST['leaved_at'] !== '') {

            $pwot->insert();
            $pwot->fetch_total_work_off();
            $arrived_at = $pwot->arrived_at->format('Y-m-d H:i:s');
            $leaved_at = $pwot->leaved_at->format('Y-m-d H:i:s');

        } else {
            echo 'Please fill out the form correctly.';
        }
    }elseif (isset($_POST['done'])){
        $query = "UPDATE daily SET worked_off = 1 WHERE id = :done";
        $stmt = $pwot->pdo->prepare($query);
        $stmt->bindParam(':done', $_POST['done']);
        $stmt->execute();
    }
}