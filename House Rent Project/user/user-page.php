<?php
include '../include/main.php';
loginSession();
if (isset($_SESSION['email'])) {
    $id = $_SESSION['uid'];
    $query = "SELECT * FROM user WHERE `uid` = $id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($user['isfiled'] == 0) {
            header("location:question.php");
            exit;
        }
    }
}
include 'header.php';
?>
<div class="container">
<div>
        <center><?php include '../message.php';?></center>
    </div>    
<br />
    <h2>Welcome : <?php echo $user['name']; ?></h2>
    <h1>Properties List</h1>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col"> Number</th>
                <th scope="col">Property type</th>
                <th scope="col">Address</th>
                <th scope="col">Description</th>
                <th scope="col">House Image</th>
                <th scope="col">Action</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM question WHERE id NOT IN (SELECT qid FROM accpect_reject WHERE `uid` =$id)";
            $result = mysqli_query($conn, $query);
            if ($result) {
                $count = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($data = mysqli_fetch_assoc($result)) {
                        if ($id !== $data['uid']) {
            ?> <tr>
                                <td><?= $count++ ?></td>
                                <td><?= $data['propertytype']; ?></td>
                                <td><?= $data['address']; ?></td>
                                <td><?= $data['description']; ?></td>
                                <td>
                                    <h1><img src="<?php echo "./../image/" . $data['image'] ?>" width="100px" height="100px"></h1>
                                </td>

                                <td><a href="user-view.php?id=<?= $data['uid'] ?>" onclick="return confirm('Are you sure you want to view the profile?')" class="btn btn-warning btn-xs" title="View Profile">View</a>
                                </td>
                            </tr>
            <?php
                        }
    }
    }
    else {
        echo "<tr><td colspan='4'>No data found.</td></tr>";
        }
}
            ?>
        </tbody>
    </table>
    </body>

    </html>