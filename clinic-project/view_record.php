<?php
include "./Database.php"; ?>
<!DOCTYPE html>
<html>

<head>
    <title>View Appointments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
        <a class="navbar-brand" href="#">Booking Appointment</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_record.php">View</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <br />
        <h1>Record</h1>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Appointment Number</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Location</th>
                    <th scope="col">Date</th>
                    <th scope="col">Shift</th>
                </tr>
            </thead>
            <?php
            $pageData = $db->selectData("SELECT * FROM  basic_record ORDER BY id DESC");
            foreach ($pageData as $data) :
            ?>

                <tbody>
                    <tr>
                        <td><?= $data['id']; ?></td>
                        <td><?= $data['phone_number'] ?></td>
                        <td><?= $data['full_name'] ?></td>
                        <td><?= ucfirst($data['location']) ?></td>
                        <td><?= $data['date'] ?></td>
                        <td><?= ucfirst($data['shift']) ?></td>
                    </tr>
                    <tr>
                    <?php endforeach; ?>
                </tbody>
        </table>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>

</html>