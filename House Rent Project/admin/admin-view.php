<?php
include '../include/main.php';
loginSession();
include 'header.php';
?>
<div class="container">
    <br />
    <h1>User Record</h1>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">User Number</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Created-date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM user";
            $result = mysqli_query($conn, $query);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $count = 1;
                    foreach ($result as $data) {
                        if ($data['type'] == 0) {
            ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= $data['name'] ?></td>
                                <td><?= $data['email'] ?></td>
                                <td><?= $data['createddate'] ?></td>

                                <td>
                                    <?php if ($data['isfiled'] == 1) { ?>
                                        <a href="view-data.php?id=<?= $data['uid'] ?>" onclick="return confirm('Are you sure you want to view the profile?')" class="btn btn-warning btn-xs" title="View Profile">View</a>
                                    <?php } else { ?>
                                        <button class="btn btn-danger btn-xs" disabled>Incomplete Signup</button>
                                    <?php } ?>
                                </td>
                            <tr>
                <?php
                        }
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