<?php
session_start();
// Include config file

require_once "config.php";
// Define variables and initialize with empty values
$email = $name = $username = $password = $confirm_password = "";
$email_err = $name_err = $username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty(trim($_POST["name"]))) {
        $username_err = "please enter a name.";
    } else {
        $param_username = trim($_POST["name"]);
        $name = trim($_POST["name"]);
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "please enter an username.";
    } else {
        // prepare a select statement
        $sql = "SELECT username FROM TaxPayer WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // set parameters
            $param_username = trim($_POST["username"]);

            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "this username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "oops! something went wrong. please try again later.";
            }

            // close statement
            mysqli_stmt_close($stmt);
        }
    }
    if (empty(trim($_POST["email"]))) {
        $email_err = "please enter an email.";
    } else {
        // prepare a select statement
        $sql = "SELECT email FROM TaxPayer WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // set parameters
            $param_email = trim($_POST["email"]);

            // attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "this email is already taken.";
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

    // validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 3) {
        $password_err = "password must have at least 3 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO TaxPayer (IsOrganization, Email, Name, Password, Username) VALUES (0, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_name, $param_password, $param_username);

            // Set parameters
            $param_username = $username;
            $param_name = $name;
            $param_email = $email;
            $param_password = $password; // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                // Save Variables to global vars
                $email = $_POST['email'];

                $query = "SELECT Name, TPID FROM TaxPayer WHERE Email = '" . $email . "'";
                // $query = "SELECT Name FROM TaxPayer WHERE Email = '" . $email . "'";
                if ($result = mysqli_query($link, $query)) {
                    while ($row = mysqli_fetch_row($result)) {
                        $name = $row[0];
                        $tpid = $row[1];
                    }
                    mysqli_free_result($result);
                } else {
                    echo "oops! something went wrong. please try again later.";
                }
                mysqli_stmt_close($result);

                $_SESSION['name'] = $name;
                $_SESSION['tpid'] = $tpid;
                $_SESSION['email'] = $email;
                header("location: dashboard.php");
            } else {
                echo "Something went wrong. Please try again later.";
                mysqli_stmt_close($stmt);
            }

            // Close statement
            // mysqli_stmt_close($stmt);
        }
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
                <li class="active"><a href="./signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a>
                </li>
                <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="page-header">
            <h1>Sign Up</h1>
        </div>
        <form action="signup.php" method="post">
            <div class="input-group">
                <label for="name">Name (First Last):</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div><br>
            <div class="input-group">
                <label for="email">Email address:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div><br>
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div><br>
            <div class="input-group">
                <label for="pwd">Password:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div><br>
            <div class="input-group">
                <label for="pwd">Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
            </div>
            <input type="submit" class="btn btn-primary" value="Sign Up">
        </form>
    </div>
</body>

</html>