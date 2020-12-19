<?php

session_start();

require_once "../config.php";
$tpid = $salary = $date = "";
$tpid_err = $salary_err = $date_err = "";
$row = 0;
$search_row = 0;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["tpid"])) {
        $tpid_err = "please enter employee's id.";
    } else {
        $tpid = $_POST["tpid"];
    }

    if (empty($_POST["salary"])) {
        $salary_err = "please enter salary.";
    } else {
        $salary = $_POST["salary"];
    }

    if (empty($_POST["date"])) {
        $date_err = "please enter date.";
    } else {
        $date = $_POST["date"];
    }


    // Check input errors before inserting in database
    if (empty($tpid_err) && empty($salary_err) && empty($date_err)) {

        // Prepare an insert statement
        $arglist = "('" . $tpid . "','" . $_SESSION['eid'] . "','" . $salary . "','" . $date . "')";
        $query = "INSERT INTO WorksFor (TPID, EID, AnnualSalary, DateJoined) VALUES " . $arglist;
        echo $query;
        if ($result = mysqli_query($link, $query)) {

            header("location: businesspage2.php");
            mysqli_free_result($result);
        } else {
            echo "oops! something went wrong. please try again later.";
        }
        mysqli_stmt_close($result);
    }
    // Close connection
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Taxulator</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../index.css">
    <script src="https://unpkg.com/pdf-lib"></script>
</head>

<body>
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="../index.html">Taxulator</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../index.html"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <p>
                    <?php
                    echo "Welcome, " .  $_SESSION['name'];
                    ?>
                </p>
                <form action="businesspage1.php" method="post">
                    <h4>Add an Employee</h4>
                    <div class="input-group">
                        <label for="tpid">Employee ID</label>
                        <input type="text" name="tpid" class="form-control" value="<?php echo $tpid; ?>">
                        <span class="help-block"><?php echo $tpid_err; ?></span>
                    </div><br>
                    <div class="input-group">
                        <label for="salary">Annual Salary</label>
                        <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                        <span class="help-block"><?php echo $salary_err; ?></span>
                    </div><br>
                    <div class="input-group">
                        <label for="date">Date Joined</label>
                        <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
                        <span class="help-block"><?php echo $date_err; ?></span>
                    </div>
                    <hr>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </form>

                <ul class="pager">
                    <li class="previous"><a href="../index.html">Quit</a></li>
                </ul>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</body>

</html>