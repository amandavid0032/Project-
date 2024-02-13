<?php
$conn = mysqli_connect("localhost", "root", "", "record") or die("Connection failed:" . mysqli_connect_error());
$search_value = mysqli_real_escape_string($conn, $_POST["search"]);
$limit = 5;
$page = "";
if (isset($_POST["page_no"])) {
    $page = $_POST["page_no"];
} else {
    $page = 1;
}
$sno = ($page - 1) * $limit + 1;
$sql = "SELECT * FROM studentrecord WHERE f_name LIKE '%{$search_value}%' OR l_name LIKE '%{$search_value}%'  LIMIT {$sno}, {$limit}";
$result = mysqli_query($conn, $sql) or die("Query Unsuccessful.");
$output = ""; ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<?php
if (mysqli_num_rows($result) > 0) {
    $output .= '
    <table class="table table-bordered table-dark">
        <tr class="table-info">
            <th scope="col">S.no</th>
            <th scope="col">Name</th>
            <th scope="col">Age</th>
            <th scope="col">Email-Id</th>
            <th scope="col">Phone-No</th>
            <th scope="col">Gender</th>
            <th scope="col">Image</th>
            <th scope="col">Action</th>
        </tr>
      ';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
                <td scope='row'>$sno</td>
                <td scope='row'>{$row['f_name']} {$row['l_name']}</td>
                <td scope='row'>{$row['age']}</td>
                <td scope='row'>{$row['emailId']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['gender']}</td>
                <td><img src='uploads/{$row['userimage']}' alt='User Image' width='50'></td>
                <td><button type='button' class='btn btn-success'  data-eid='{$row['id']}'>Edit</button>
                <button type='button' class='btn btn-danger' data-id='{$row['id']}'>Delete</button></td>            
                </tr>
             ";
        $sno++;
    }
    $output .= " </table>";
    $sql_total =  "SELECT * FROM studentrecord WHERE f_name LIKE '%{$search_value}%'";
    $records = mysqli_query($conn, $sql_total) or die("Query Unsuccessful.");
    $totalRecords = mysqli_num_rows($records);
    $totalPages = ceil($totalRecords / $limit);
    $output .= '<div id="paginationid"><center><nav aria-label="Page navigation example"><ul class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $class_name = ($i == $page) ? "active" : "";
        $output .= "<li class='page-item {$class_name}'><a class='page-link' id='{$i}' href='?search={$search_value}&page_no={$i}'>{$i}</a></li>";
    }
    $output .= "</ul></nav></center></div>";
    echo $output;
} else {
    echo "<h2>No Record Found.";
}
?>