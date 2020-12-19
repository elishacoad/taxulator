<?php

session_start();

require_once "../config.php";
$income = $deduct = $deduct2 = $ctax = "";
$income_err = $deduct_err = $deduct2_err = $ctax_err = "";
$row = 0;
$search_row = 0;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["isource"])) {
        $isource_err = "please enter income source.";
    } else {
        $isource = $_POST["isource"];
    }

    if (empty($_POST["income"])) {
        $income_err = "please enter income.";
    } else {
        $income = $_POST["income"];
    }

    if (empty($_POST["deduct"])) {
        $deduct_err = "please enter a deductible.";
    } else {
        $deduct = $_POST["deduct"];
    }

    if (empty($_POST["deduct2"])) {
        $deduct2_err = "please enter a deductible.";
    } else {
        $deduct2 = $_POST["deduct2"];
    }

    if (empty(trim($_POST["ctax"]))) {
        $ctax_err = "please enter an ctax.";
    } else {
        $ctax = trim($_POST["ctax"]);
        $query = "SELECT TaxMinimum FROM CityTaxes WHERE CityID = '" . $ctax . "'";
        if ($result = mysqli_query($link, $query)) {
            while ($row = mysqli_fetch_row($result)) {
                // printf("%s\n", $row[0]);
                $citymin = $row[0];
            }
            mysqli_free_result($result);
        } else {
            echo "oops! something went wrong. please try again later.";
        }
        mysqli_stmt_close($result);
    }


    // Check input errors before inserting in database
    if (empty($income_err) && empty($deduct_err) && empty($deduct2_err) && empty($ctax_err)) {
        $tpid = $_SESSION['tpid'];
        $atincome = $income - $deduct - $deduct2;

        // Prepare an insert statement
        $arglist = "('00010','" . $tpid . "','" . $isource . "','" . $income . "','" . $atincome . "','" . $ctax . "','NA', 'NA')";
        $query = "INSERT INTO Session (SID, TPID, IncomeSource, PreDeduction, AdjustedTaxableIncome, CityID, TaxReturnDetails, Report) VALUES " . $arglist;
        if ($result = mysqli_query($link, $query)) {
            if ($param_ati < 5000) {
                $taxrate = .10;
            } else if ($param_ati < 11000) {
                $taxrate = .15;
            } else if ($param_ati < 18000) {
                $taxrate = .20;
            } else if ($param_ati < 35000) {
                $taxrate = .25;
            } else {
                $taxrate = .30;
            }

            $taxdue = $atincome * $taxrate;
            if ($taxdue < $citymin) {
                $taxdue = $citymin;
            }
            $_SESSION['gtincome'] = $income;
            $_SESSION['tincome'] = $income - $deduct;
            $_SESSION['atincome'] = $atincome;
            $_SESSION['citymin'] = $citymin;
            $_SESSION['taxdue'] = $taxdue;

            header("location: page2.php");
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
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <p>
                    <?php
                    echo "You are currently logged in as " .  $_SESSION['name'];
                    ?>
                </p>
                <form action="page1.php" method="post">
                    <div class="input-group">
                        <label for="isource">Income source:</label>
                        <input type="text" name="isource" class="form-control" value="<?php echo $isource; ?>">
                        <span class="help-block"><?php echo $isource_err; ?></span>
                    </div>
                    <div class="input-group">
                        <label for="income">Gross Taxable Income</label>
                        <input type="text" name="income" class="form-control" value="<?php echo $income; ?>">
                        <span class="help-block"><?php echo $income_err; ?></span>
                    </div>
                    <h4>Taxable Income</h4>
                    <dl>
                        <li>Allowed a maximum of 2 of the following deductions:</li>
                        <li><b>Basic Deduction</b> - $3,000</li>
                        <li><b>Female or Senior Citizen Deduction</b> (age 65 or over) - $500</li>
                        <li><b>Handicap Deduction</b> - $750</li>
                        <li><b>Wounded Veteran Deduction</b> - $2,000</li>
                    </dl>
                    <div class="input-group">
                        <label for="deduct">Sum of Deductibles</label>
                        <input type="text" name="deduct" class="form-control" value="<?php echo $deduct; ?>">
                        <span class="help-block"><?php echo $deduct_err; ?></span>
                    </div>
                    <h4>Adjusted Taxable Income</h4>
                    <dl>
                        <li>Include the sum of the following:</li>
                        <li>Student loans including interests</li>
                        <li>Rental home repair costs owned by the tax payer</li>
                        <li>Any job-related expenses such as travel expenses</li>
                    </dl>
                    <div class="input-group">
                        <label for="deduct2">Sum of Additional Deductibles</label>
                        <input type="text" name="deduct2" class="form-control" value="<?php echo $deduct2; ?>">
                        <span class="help-block"><?php echo $deduct2_err; ?></span>
                    </div>
                    <div class="input-group">
                        <label for="ctax">Your city tax id</label>
                        <input type="text" name="ctax" class="form-control" value="<?php echo $ctax; ?>">
                        <span class="help-block"><?php echo $ctax_err; ?></span>
                    </div>
                    <hr>
                    <input type="submit" class="btn btn-primary" value="Continue">
                </form>

                <ul class="pager">
                    <li class="previous"><a href="../dashboard.php">Quit</a></li>
                </ul>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>
</body>

</html>