<?php
session_start();
// Include config file

require_once "config.php";
// Define variables and initialize with empty values
$email = $name = $password = "";
$email_err = $name_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["email"]))) {
        $email_err = "please enter an email.";
    } else {
        // prepare a select statement
        $sql = "SELECT email FROM Employers WHERE email = ?";
        // $sql = "SELECT email FROM TaxPayer WHERE email = ? AND [password] = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // mysqli_stmt_bind_param($stmt, "s", $param_email, $param_password);

            // set parameters
            $param_email = trim($_POST["email"]);
            // $param_password = $_POST["password"];

            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 0) {
                    $email_err = "this email is not registered";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "oops! something went wrong. please try again later.";
            }

            // close statement
            mysqli_stmt_close($stmt);
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "please enter a password.";
    } else {
        // prepare a select statement
        $sql = "SELECT email FROM Employers WHERE password = ?";
        // $sql = "SELECT email FROM TaxPayer WHERE email = ? AND [password] = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_password);
            // mysqli_stmt_bind_param($stmt, "s", $param_email, $param_password);

            // set parameters
            $param_password = trim($_POST["password"]);
            // $param_password = $_POST["password"];

            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 0) {
                    $password_err = "this password is not registered with corresponding email";
                } else {
                    $password = trim($_POST["password"]);
                }
            } else {
                echo "oops! something went wrong. please try again later.";
            }

            // close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Check input errors
    if (empty($email_err) && empty($password_err)) {
        // Save Variables to global vars
        $email = $_POST['email'];

        $query = "SELECT Name, EID FROM Employers WHERE Email = '" . $email . "'";
        // $query = "SELECT Name FROM TaxPayer WHERE Email = '" . $email . "'";
        if ($result = mysqli_query($link, $query)) {
            while ($row = mysqli_fetch_row($result)) {
                $name = $row[0];
                $eid = $row[1];
            }
            mysqli_free_result($result);
        } else {
            echo "oops! something went wrong. please try again later.";
        }
        mysqli_stmt_close($result);

        $_SESSION['name'] = $name;
        $_SESSION['eid'] = $eid;
        $_SESSION['email'] = $email;
        header("location: Session/businesspage1.php");
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
    <link rel="stylesheet" href="./index.css">
</head>

<body>

    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.html">Taxulator</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="./signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                <li class="active"><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Business Login</h1>
        </div>
        <p>Are you a tax payer? <a href="./login.php">Click Here</a></p>
        <form action="business_login.php" method="post">
            <div class="input-group">
                <label for="email">Business Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div><br>
            <div class="input-group">
                <label for="pwd">Password:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
            </div>
            <input type="submit" class="btn btn-primary" value="Login">
        </form>
    </div>
</body>

</html>