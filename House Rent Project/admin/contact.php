<?php
include "../include/main.php";
loginSession();
include 'header.php';
?>
<div class="container">
    <br />
    <h1>Query Record</h1>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Query Number</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Message</th>
                <th scope="col">Created-data</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM contact ";
            $result = mysqli_query($conn, $query);
            $count = 1;
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $data) {
            ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= $data['name']; ?></td>
                            <td><?= $data['email']; ?></td>
                            <td><?= $data['message'] ?></td>
                            <td><?= $data['date'] ?></td>
                        </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>No data found.</td></tr>";
                }
            } ?>
        </tbody>
    </table>
</div>
</body>

</html>