<?php
include "../include/main.php";
loginSession();
include 'header.php';
?>
<div class="container">
    <br />
    <h1>Reject Record</h1>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col"> Number</th>
                <th scope="col">Property type</th>
                <th scope="col">Address</th>
                <th scope="col">Description</th>
                <th scope="col">House Image</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $email = $_SESSION['email'];
            $id = $_SESSION['uid'];
            $query = "   SELECT q.id,q.image, q.propertytype, q.address, q.description FROM question AS q
            INNER JOIN accpect_reject AS a ON a.qid = q.id
            WHERE a.uid =$id AND a.types=0";
            $result = mysqli_query($conn, $query);
            $count = 1;
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $data) {
            ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= $data['propertytype']; ?></td>
                            <td><?= $data['address']; ?></td>
                            <td><?= $data['description']; ?></td>
                            <td>
                                <h1><img src="<?php echo "./../image/" . $data['image'] ?>" width="100px" height="100px"></h1>
                            </td>
                        </tr>
            <?php

                    }
                } else {
                    echo "<tr><td colspan='4'>No data found.</td></tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>
</body>

</html>