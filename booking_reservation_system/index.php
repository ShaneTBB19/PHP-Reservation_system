<?php
    $local =  "localhost"; // Host Name
    $user  =  "root"; // Host Username
    $pass  =  ""; // Host Password
    $db    =  "booking_reservation"; // Database Name

    // Establish connection to the database
    $con = new mysqli($local, $user, $pass, $db);
    // if there is a problem, show error message
    if ($con->connect_errno) {
        echo "Failed to connect to MySQL: " . $con->connect_error;
        exit();
    }

    if(isset($_POST["btnReserve"])) {
        $name = trim($con->real_escape_string($_POST["txtName"]));
        $room = trim($con->real_escape_string($_POST["txtRoom"]));
        $date = trim($con->real_escape_string($_POST["txtDate"]));

        if ($stmt = $con->prepare("INSERT INTO `reservations`(`name`, `room`, `date_reserved`) VALUES (?, ?, ?)")) {
            $stmt->bind_param("sss", $name, $room, $date);
            $stmt->execute();
            $stmt->close();
        } else {
            die('prepare() failed: ' . htmlspecialchars($con->error));
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simple Room Reservation System in PHP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/c82e9a37b7.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <style>
        body {
            background: #e9ecef;
            font-family: sans-serif;
        }
    </style>
</head>
<body class="py-5">
    <main class="container">
        <div class="row">
            <div class="offset-2 col-8 font-size-xl">
                <div class="bg-secondary text-light rounded  text-center p-3">
                    <h2 class="font-weight-bold">Simple Room Reservation System in PHP</h2>
                    <h3>&#187; Developed By Shane Bema &#171;</h3>
                </div>
                <form action="" method="post" class="my-5">
                    <div class="form-group">
                        <label for="txtName">Name:</label>
                        <input type="text" class="form-control" name="txtName" required>
                    </div>
                    <div class="form-group">
                        <label for="txtRoom">Room Type:</label>
                        <select class="form-control" name="txtRoom" required>
                            <option value="Regular">Regular</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="VIP Suite">VIP Suite</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="txtDate">Date:</label>
                        <input type="text" class="form-control datepicker" name="txtDate" id="txtDate" required>
                    </div>
                    <button type="submit" class="btn btn-dark" name="btnReserve">Reserve Now!</button>
                </form>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="offset-2 col-8 mt-5">
                <table class="table table-striped table-bordered" id="datatable">
                    <legend class="bg-secondary p-3 text-light text-center rounded">
                        <h2 class="m-0">Reservations Table</h2>
                    </legend>
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Room Type</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                        <?php                         
                            // Display database table data
                            if ($stmt = $con->prepare("SELECT * FROM `reservations`")) {
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>".
                                            "<td>".$row["id"]."</td>".
                                            "<td>".$row["name"]."</td>".
                                            "<td>".$row["room"]."</td>".
                                            "<td>".$row["date_reserved"]."</td>".
                                        "</tr>";
                                }
                                $stmt->close();
                            } else {
                                die('prepare() failed: ' . htmlspecialchars($con->error));
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                todayBtn: 'linked',
                startDate: 'today',
            });

            $('#datatable').DataTable();

            $('#txtDate').keypress(function(e) {
                e.preventDefault();
            });
        });
    </script>
</body>
</html>