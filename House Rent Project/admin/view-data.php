<?php
include '../include/main.php';
loginSession();
$id = $_GET['id'];
$query = "SELECT q.id, q.uid, q.image, q.roomimage, q.propertytype, q.bedrooms, q.bathrooms, q.address, q.description, q.laundry, q.parking, q.ac, q.heating, q.amenities
FROM question AS q
INNER JOIN user AS u ON u.uid = q.uid
WHERE q.uid =$id";
$result = mysqli_query($conn, $query);
foreach ($result as $data) {
    include 'header.php';
?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Image</span>
                    <span class="badge badge-secondary badge-pill"></span>
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">House Image</h6>
                            <h1><img src="<?php echo "./../image/" . $data['image'] ?>" width="390px" height="300px"></h1>
                        </div>
                    </li>

                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Room Image</h6>
                            <h1><img src="<?php echo "./../image/" . $data['roomimage'] ?>" width="390px" height="300px"></h1>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Infromation</h4>
                <form novalidate="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">Propert Type</label>
                            <input readonly class="form-control" id="firstName" placeholder="" value="<?php echo $data['propertytype'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">BeedRooms</label>
                            <input readonly class="form-control" value="<?php echo $data['bedrooms'] ?>">

                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="lastName">Bathroom</label>
                            <input readonly class="form-control" id="lastName" placeholder="" value="<?php echo $data['bathrooms'] ?>" required="">
                        </div>

                        <div class="mb-3">
                            <label for="username">Address</label>
                            <input readonly class="form-control" id="zip" value="<?php echo $data['address'] ?>">

                        </div>

                        <div class="mb-3">
                            <label for="email">Description</label>
                            <input readonly class="form-control" value="<?php echo $data['description'] ?> ">
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Landury</label>
                                <input readonly class="form-control" value=" <?php echo $data['laundry'] ?>">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="country">Parking</label>
                                <input readonly class="form-control" value="<?php echo $data['parking'] ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Ac</label>
                                <input readonly class="form-control" value="<?php echo $data['ac'] ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state">Heating</label>
                                <input readonly class="form-control" value="<?php echo $data['heating'] ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Amenities</label>
                                <input readonly class="form-control" value="<?php echo $data['amenities'] ?>">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            </body>

            </html>