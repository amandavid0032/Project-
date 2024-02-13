<?php
include '../include/main.php';
loginSession();
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT * FROM user WHERE email = '$email'";;
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <!-- Tailwind CSS CDN link -->
            <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="styles.css">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f2f2f2;
                }

                form {
                    max-width: 500px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 5px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                }

                h2 {
                    font-size: 24px;
                    margin-bottom: 20px;
                    text-align: center;
                }

                div {
                    margin-bottom: 15px;
                }

                label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                input[type="radio"],
                input[type="checkbox"] {
                    margin-right: 5px;
                }

                textarea,
                input[type="text"],
                input[type="number"],
                select {
                    width: 100%;
                    padding: 10px;
                    border-radius: 3px;
                    border: 1px solid #ccc;
                    box-sizing: border-box;
                }

                button[type="submit"] {
                    background-color: #4caf50;
                    color: #fff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 3px;
                    cursor: pointer;
                    font-size: 16px;
                }

                button[type="submit"]:hover {
                    background-color: #45a049;
                }

                button[type="submit"]:focus {
                    outline: none;
                }
            </style>
        </head>
        <div class="relative w-full bg-white">
            <nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
                <a class="navbar-brand" href="user-page.php">Home</a>
                <a href="../logout.php"><button type="button" type="logout" class="float-right">
                        Logout
                    </button></a>
        </div>
        </nav>
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 sm:px-6 lg:px-8">
            <div class="lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 cursor-pointer">
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </div>
        </div>
        </div>

        <form id="questionnaire" method="post" enctype="multipart/form-data">
            <h2>Welcome <?php echo $user['name'] ?>
            </h2>
            <h2>Landlord Questionnaire</h2>
            <div>
                <label>House Image</label>
                <input type="file" name="imagename" id="fileToUpload">
            </div>
            <div>
                <label>Room Image</label>
                <input type="file" name="image" id="fileToUpload">
            </div>
            <div>
                <label id="value" for="property-type">Property Type:</label>
                <div>
                    <label class="radio-label" for="property-flat">
                        <input type="radio" id="property-flat" name="property-type" required value="Flat">
                        Flat
                    </label>
                    <label class="radio-label" for="property-house">
                        <input type="radio" id="property-house" name="property-type" required value="House">
                        House
                    </label>
                    <label class="radio-label" for="property-townhouse">
                        <input type="radio" id="property-townhouse" name="property-type" required value="Townhouse">
                        Townhouse
                    </label>
                </div>
            </div>
            <div>
                <label for="bedrooms">Number of Bedrooms:</label>
                <input type="number" name="bedrooms" id="bedrooms" required min="1">
            </div>
            <div>
                <label for="bathrooms">Number of Bathrooms:</label>
                <input type="number" id="bathrooms" name="bathrooms" required min="1">
            </div>
            <div>
                <label for="address">Property Address:</label>
                <input type="text" id="address" required name="address">
            </div>
            <div>
                <label for="description">Property Description:</label>
                <textarea id="description" required name="description"></textarea>
            </div>
            <div>
                <label for="laundry-type">Laundry Type:</label>
                <select id="laundry-type" required name="value">
                    <option value="None">None</option>
                    <option value="In-unit">In-unit</option>
                    <option value="Shared">Shared</option>
                </select>
            </div>
            <div>
                <label id="value"  for="parking-type">Parking Type:</label>
            </div>
            <div>
                <label for="parking-garage" class="radio-label">
                    <input type="radio" id="parking-street" name="parking-type" value="Garage" required>Garage</label>
                <label for="parking-street" class="radio-label">
                    <input type="radio" id="parking-off-street" name="parking-type" value="Street" required>Street</label>
                <label for="parking-off-street" class="radio-label">
                    <input type="radio" id="parking-available" name="parking-type" value="off Street" required>Off Street</label>
                <label for="parking-available" class="radio-label">
                    <input type="radio" id="parking-none" name="parking-type" value="None"required>None</label>
            </div>
            <div>
                <label id="value" for="ac-type">Air Conditioning Type:</label>
            </div>
            <div>
                <label for="ac-central" class="radio-label">
                    <input type="radio" id="ac-central" name="ac-type" value="Central AC" required>
                    Central AC</label>
                <label for="ac-available" class="radio-label">
                    <input type="radio" id="ac-available" name="ac-type" value="AC Available" required>AC Available</label>
                <label for="ac-none" class="radio-label">
                    <input type="radio" id="ac-none" name="ac-type" value="None" required>None</label>
            </div>
            <div>
                <label name="heating" for="heating-available">Heating Available:</label>
            </div>
            <div>
                <label for="heating-yes" class="radio-label">
                    <input type="radio" id="heating-yes" name="heating-available" value="Yes" required>Yes</label>
                <label for="heating-no" class="radio-label">
                    <input type="radio" id="heating-no" name="heating-available" value="No" required>
                    No</label>
            </div>
            <div>
                <label required for="amenities">Amenities:</label>
            </div>
            <label for="amenities-pool" class="radio-label">
                <input type="checkbox" id="amenities-pool" name="amenities[]" value="Pool" >Pool</label>
            <label for="amenities-gym" class="radio=label">
                <input type="checkbox" id="amenities-gym" name="amenities[]" value="Gym" >Gym</label>
            <label for="amenities-park" class="radio-label">
                <input type="checkbox" id="amenities-park" name="amenities[]" value="Park" >Park</label>
            </div>
            <button name="question" type="submit">Submit</button>
        </form>
        <script src="index.js">
        </script>
        </body>
<?php     }
} ?>
</body>

        </html>