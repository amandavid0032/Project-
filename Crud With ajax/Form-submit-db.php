<?php
$conn = mysqli_connect("localhost", "root", "", "record") or die("Connection failed:" . mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phoneno = $_POST['phone'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $imagename = $_FILES['imagename']['name'];
    $imagetmp = $_FILES['imagename']['tmp_name'];
    $uploads_dir = 'uploads/';
    move_uploaded_file($imagetmp, $uploads_dir . $imagename);

    $sql = $conn->prepare("INSERT INTO studentrecord (`f_name`, `l_name`, `age`, `emailId`, `phone`, `gender`, `userimage`) VALUES (?,?,?,?,?,?,?)");
    $sql->bind_param("sssssss", $firstname, $lastname, $age, $email, $phoneno, $gender, $imagename);
    $sql->execute();
    if ($sql) {
        $message = 'Your Record Added successfully';
        $color = 'success';
        header("location: User-view.php?message=" . urlencode($message) . "&color=$color");
        exit();
    } else {
        $message = 'Your Record Not Added';
        $color = 'danger';
        header("location: index.php?message=" . urlencode($message) . "&color=$color");
        exit();
    }
}
mysqli_close($conn);

if (isset($_GET['message'])) :
?>
    <div class="alert alert-<?= $_GET['color'] ?> my-3" role="alert" id="feedback">
        <?= urldecode($_GET['message']); ?>
    </div>
<?php endif; ?>
<script>
    setTimeout(() => {
        document.querySelector('#feedback').style.display = 'none';
    }, 10000);
</script>