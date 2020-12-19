<?php
session_start();


require_once "config.php";

// Define variables and initialize with empty values
$email = $name = $password = $confirm_password = "";
$email_err = $name_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty(trim($_POST["name"]))) {
        $username_err = "please enter a name.";
    } else {
        $param_username = trim($_POST["name"]);
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "please enter an email.";
    } else {
        // prepare a select statement
        $sql = "SELECT email FROM Employers WHERE email = ?";

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
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        $arglist = "('" . $name . "','" . $email . "','" . $password . "')";
        $query = "INSERT INTO Employers (Name, Email, Password) VALUES " . $arglist;

        if ($result = mysqli_query($link, $query)) {

            $query = "SELECT EID FROM Employers WHERE Email = '" . $email . "'";
            echo $query;
            if ($result = mysqli_query($link, $query)) {
                echo "success";
                while ($row = mysqli_fetch_row($result)) {
                    $eid = $row[0];
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
            mysqli_free_result($result);
        } else {
            echo "oops! something went wrong. please try again later.";
        }
        mysqli_stmt_close($result);

        // // Prepare an insert statement
        // $sql = "INSERT INTO Employers (Name, Email, Password) VALUES (?, ?, ?)";

        // if ($stmt = mysqli_prepare($link, $sql)) {
        //     // Bind variables to the prepared statement as parameters
        //     mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);
        //     echo $stmt;

        //     // Attempt to execute the prepared statement
        //     if (mysqli_stmt_execute($stmt)) {
        //         mysqli_stmt_close($stmt);

        //         $query = "SELECT Name, EID FROM Employers WHERE Email = '" . $email . "'";
        //         echo $query;
        //         if ($result = mysqli_query($link, $query)) {
        //             echo "success";
        //             while ($row = mysqli_fetch_row($result)) {
        //                 $name = $row[0];
        //                 $eid = $row[1];
        //             }
        //             mysqli_free_result($result);
        //         } else {
        //             echo "oops! something went wrong. please try again later.";
        //         }
        //         mysqli_stmt_close($result);

        //         $_SESSION['name'] = $name;
        //         $_SESSION['eid'] = $eid;
        //         $_SESSION['email'] = $email;
        //         header("location: ../businesspage2.php");
        //     } else {
        //         echo "Something went wrong. Please try again later.";
        //         mysqli_stmt_close($stmt);
        //     }
        // } else {
        //     echo "Something went wrong. Please try again later.";
        // }
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
            <h1>Business Sign Up</h1>
        </div>
        <p>Are you a tax payer? <a href="./signup.php">Click Here</a></p>
        <form action="business_signup.php" method="post">
            <div class="input-group">
                <label for="name">Company Name:</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div><br>
            <div class="input-group">
                <label for="email">Email address:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
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