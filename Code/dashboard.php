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
    <link rel="stylesheet" href="./index.css">
</head>

<body>
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.html">Taxulator</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="./index.html"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="page-header">
            <h4>
                <?php
                echo "Hello " .  $_SESSION['name'];
                ?>
            </h4>
            <h1>Dashboard</h1>
        </div>
        <a type="button" class="btn btn-primary btn-block" href="./Session/page1.php">Start New Session</a>

        <h2>Previous Sessions</h2>
        <div class="well">
            <table class="table">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Date</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>3</td>
                        <td>11-12-20</td>
                        <td><a>Ses3 11-12-20.pdf</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>10-02-20</td>
                        <td><a>Ses2 10-02-20.pdf</a></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>03-12-19</td>
                        <td><a>Ses1 03-12-19.pdf</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>