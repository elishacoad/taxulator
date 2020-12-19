<?php

session_start();
require_once "../config.php";
$employer = "";

$query = "SELECT Name FROM Employers WHERE EID = (SELECT EID FROM WorksFor WHERE TPID = '" . $_SESSION['tpid'] . "')";
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
        $employer = $row[0];
    }
    if (empty($employer)) {
        $employer = "Unknown";
    }
    mysqli_free_result($result);
} else {
    echo "oops! something went wrong. please try again later.";
}
mysqli_stmt_close($result);
mysqli_close($link);
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
                    echo "You are currently logged in as " .  $_SESSION['name'];
                    ?>
                </p>
                <h4>Success! Here is your report:</h4>
                <iframe id="pdf" style="width: 100%; height: 800px;"></iframe>

                <ul class="pager">
                    <li class="previous"><a href="../dashboard.php">Back to Dashboard</a></li>
                </ul>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</body>
<script>
    createPdf()
    var date = new Date();
    async function createPdf() {
        const pdfDoc = await PDFLib.PDFDocument.create()
        const page = pdfDoc.addPage()
        page.drawText('Tax Report', {
            x: 50,
            y: 750,
            size: 35
        })
        page.drawText('Date: '.concat(date.getMonth(), "/", date.getDate(), "/", date.getFullYear()), {
            x: 350,
            y: 780,
            size: 14
        })
        page.drawText('Name: '.concat("<?php echo $_SESSION['name']; ?>"), {
            x: 350,
            y: 760,
            size: 14
        })
        page.drawText('Works At: '.concat("<?php echo $employer; ?>"), {
            x: 350,
            y: 740,
            size: 14
        })
        page.drawText('Gross Taxable Income: $'.concat("<?php echo number_format($_SESSION['gtincome']); ?>"), {
            x: 50,
            y: 650,
            size: 18
        })
        page.drawText('Taxable Income: $'.concat("<?php echo number_format($_SESSION['tincome']); ?>"), {
            x: 50,
            y: 600,
            size: 18
        })
        page.drawText('Adjusted Taxable Income: $'.concat("<?php echo number_format($_SESSION['atincome']); ?>"), {
            x: 50,
            y: 550,
            size: 18
        })
        page.drawText('Tax Rate (based on graduated scale): '.concat("<?php echo (100 * $_SESSION['taxrate']); ?>", "%"), {
            x: 50,
            y: 500,
            size: 18
        })
        page.drawText('Min City Tax: $'.concat("<?php echo number_format($_SESSION['citymin']); ?>"), {
            x: 50,
            y: 450,
            size: 18
        })
        page.drawText('Taxes Due: $'.concat("<?php echo number_format($_SESSION['taxdue']); ?>"), {
            x: 50,
            y: 400,
            size: 18
        })

        // pdfDoc.asPDFName('Tax Report')

        // page.moveTo(110, 200);
        // page.drawText('Tax Due: 4555');
        const pdfBytes = await pdfDoc.save()
        const pdfDataUri = await pdfDoc.saveAsBase64({
            dataUri: true
        })
        document.getElementById('pdf').src = pdfDataUri
    }
</script>

</html>