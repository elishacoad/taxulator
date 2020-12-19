<?php

session_start();

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
                <h4>Successfully added employee!</h4>
                <ul class="pager">
                    <li class="previous"><a href="../index.html">Quit</a></li>
                    <li class="next"><a href="./businesspage1.php">Add Another</a></li>
                </ul>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</body>

</html>