<?php
include "./Database.php";
if (isset($_POST['add'])) {
 
    $full_name = $db->filterPostData($_POST['full_name']);
    $phone_number = $db->filterPostData($_POST['phone_number']);
    $location = $db->filterPostData($_POST['location']);
    $shift = $db->filterPostData($_POST['shift']);
    $Date = $db->filterPostData($_POST['date']);
    $created_dt = $db->getCurrentTime();

    $insert = "INSERT into basic_record value(null,'$full_name','$phone_number','$location','$shift','$date', '$created_dt');";
    $check = $db->inputData($insert);

    $message = $check ? 'Appoint booked successfully and Your booking number is : ' . $check : 'Booking Failed';
    $color = $check ? 'success' : 'danger';
    header("location:index.php?message=" . urlencode($message) . "&color=$color");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Book Appointment</title>
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
        <div class="row col-md-6">
            <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onsubmit="return confirm('Are you sure you want to Book Appointment ?')">
                <?php include './message.php'; ?>
                <div class="form-group my-3">
                    <label for="full_name">Full Name</label>
                    <input type="name" name="full_name" id="full_name" class="form-control" placeholder="Enter Full Name" required>
                </div>
                <div class="form-group my-3">
                    <label for="phone_number">Phone Number</label>
                    <input type="number" min="0" name="phone_number" id="phone_number" class="form-control" placeholder="Enter Phone Number" required>
                </div>
                <div class="form-group my-3">
                    <label for="Date">Date</label>
                    <input type="date" min="0" name="date" id="selectedDate" class="form-control" placeholder="Enter Date" required>
                </div>
                <div class="form-group my-3">
                    <label for="location">Select Location</label><br>
                    <select name="location" id="location" class="form-control" required>
                        <option class="form-control" value="">Select Location</option>
                        <option class="form-control" value="gurgaon">Gurgaon</option>
                        <option class="form-control" value="palam">Palam</option>
                        <option class="form-control" value="dwarka">Dwarka</option>
                        <option class="form-control" value="samaoli">Samaoli</option>
                        <option class="form-control" value="bhiwadi">Bhiwadi</option>
                    </select>
                </div>
                <div class="form-group my-3">
                    <label for="shift" id="day_select">Select Shift</label><br>
                    <select name="shift" class="form-control" id="shift_day">
                        <option class="form-control" value="">Select Shift</option>
                    </select>
                </div>
                <div class="form-group my-3">
                    <button type="submit" name="add" class="btn btn-success">Book</button>
                    <button type="submit" class="btn btn-info">Clear</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        let selectDay = '';
        let shift = '';

        function getDayOfWeek(date) {
            let getDayFromDate = new Date(date).getDay();
            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return dayNames[getDayFromDate];
        }

        function getShift(location, day) {
            const shifts = {
                "gurgaon": {
                    "Sunday": ['Holiday'],
                    "Monday": ['Morning', "Evening"],
                    "Tuesday": ['Morning', "Evening"],
                    "Wednesday": ['Morning', "Evening"],
                    "Thursday": ['Morning', "Evening"],
                    "Friday": ['Morning', "Evening"],
                    "Saturday": ['Morning', "Evening"]
                },
                "palam": {
                    "Sunday": ['Holiday'],
                    "Monday": ['Holiday'],
                    "Tuesday": ['Holiday'],
                    "Wednesday": ['Morning'],
                    "Thursday": ['Holiday'],
                    "Friday": ['Holiday'],
                    "Saturday": ['Holiday']
                },
                "dwarka": {
                    "Sunday": ['Holiday'],
                    "Monday": ['Holiday'],
                    "Tuesday": ['Holiday'],
                    "Wednesday": ['Evening'],
                    "Thursday": ['Holiday'],
                    "Friday": ['Holiday'],
                    "Saturday": ['Holiday']
                },
                "samaoli": {
                    "Sunday": ['Holiday'],
                    "Monday": ['Holiday'],
                    "Tuesday": ['Holiday'],
                    "Wednesday": ['Holiday'],
                    "Thursday": ['Holiday'],
                    "Friday": ['Holiday'],
                    "Saturday": ['Holiday']
                },
                "bhiwadi": {
                    "Sunday": ['Morning', "Evening"],
                    "Monday": ['Morning', "Evening"],
                    "Tuesday": ["Holiday"],
                    "Wednesday": ['Morning', "Evening"],
                    "Thursday": ['Morning', "Evening"],
                    "Friday": ['Morning', "Evening"],
                    "Saturday": ['Morning', "Evening"]
                },
            };
            return shifts[location][day];
        }
        let selectedDateOption = document.querySelector('#selectedDate');
        selectedDateOption.addEventListener('change', function(e) {
            let getDate = e.target.value;
            selectDay = getDayOfWeek(getDate)
        });
        let locationOption = document.querySelector('#location');
        locationOption.addEventListener('change', function(e) {
            let optionValue = '';
            let getShiftValues = getShift(e.target.value, selectDay);
            if (getShiftValues[0] !== 'Holiday') {
                getShiftValues.forEach(item => {
                    optionValue += `<option value='${item}'>${item}</option>`;
                })
            }
            document.querySelector('#shift_day').innerHTML = optionValue;
        });
    </script>
</body>

</html>