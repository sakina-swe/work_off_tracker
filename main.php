<?php

declare(strict_types=1);

require 'pwot.php';

$workday = new Pwot();
$workday_list = $workday->data;
?>

<form action="main.php" method="post">
    <label>
        Arrived At
        <input type="datetime-local" name="arrived_at"> <br>
    </label>

    <label>
        Leaved At
        <input type="datetime-local" name="leaved_at"> <br>
    </label>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<div class="container">

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Arrived at</th>
        <th scope="col">Leaved at</th>
        <th scope="col">Required work off</th>
        <th scope="col">Worked off</th>

    </tr>
    </thead>

    <tbody>
    <?php
    foreach ($workday_list as $day) : ?>
        <tr class="<?php echo $day['worked_off'] ? 'table-success' : ''; ?>">
        <td><?php
            echo $day['id']; ?></td>
        <td><?php
            echo $day['arrived_at']; ?></td>
        <td><?php
            echo $day['leaved_at']; ?></td>
        <td><?php
            echo $day['required_work_off']; ?></td>
        <td>
            <?php
                if ($day['worked_off']){?>
                        <label>

                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked disabled>
                        Done</label>
                <?php
                }else{?>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Done
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-footer">
                                    <form action="main.php" method="post">
                                        <input type="hidden" name="done" value="<?php echo $day['id']; ?>">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                        <button type="submit" class="btn btn-primary">Yes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
            }
            ?>
        </td>
        </tr>
    <?php
    endforeach;
    ?>
    </tbody>
    <tfoot>
    <tr><th colspan="3">Total work off hours</th>
    <th colspan="2"><?php echo $workday->get_total_work_off() ?></th></tr>
    </tfoot>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>