<?php

declare(strict_types=1);
date_default_timezone_set("Asia/Tashkent");
?>

    <form action="sakina_makhmudova2706.php" method="post">
        <label>
            Arrived At
            <input type="datetime-local" name="arrived_at"> <br>
        </label>

        <label>
            Leaved At
            <input type="datetime-local" name="leaved_at"> <br>
        </label>
        <button>Submit</button>
    </form>

<?php

class Daily{

    public $pdo;

    public function __construct(){
        $this->pdo = new PDO('mysql:host=localhost;dbname=work_off_tracker',
            'root',
            '1234');
    }

    public function addItems($arrived_at, $leaved_at): void {
        $query = "INSERT INTO daily (arrived_at, leaved_at)
                      VALUES (:arrived_at, :leaved_at)";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':arrived_at', $arrived_at);
        $stmt->bindParam(':leaved_at', $leaved_at);
        $stmt->execute();
    }

    public function output(): void {
        $stmt = $this->pdo->query('SELECT * FROM daily');
        $data = $stmt->fetchAll();

        echo "<ul>";
        foreach ($data as $time) {
            $arrivedAt = $time['arrived_at'] ? htmlspecialchars($time['arrived_at']) : 'N/A';
            $leavedAt = $time['leaved_at'] ? htmlspecialchars($time['leaved_at']) : 'N/A';
            echo "<li>Arrived at: " . $arrivedAt . " - Leaved at: " . $leavedAt . "</li>";
        }
        echo "</ul>";
    }
}

$daily = new Daily();
if (!empty($_POST)) {
    if ($_POST['arrived_at'] !== '' && $_POST['leaved_at'] !== '') {
        $arrived_at = (new DateTime($_POST['arrived_at']))->format('Y-m-d H:i:s');
        $leaved_at = (new DateTime($_POST['leaved_at']))->format('Y-m-d H:i:s');

        $daily->addItems($arrived_at, $leaved_at);
        $daily->output();
    } else {
        echo 'Please fill out the form correctly.';
    }
}
?>