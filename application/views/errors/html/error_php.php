<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Error</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>A PHP Error was encountered</h1>
    <p>Severity: <?php echo $severity; ?></p>
    <p>Message:  <?php echo $message; ?></p>
    <p>Filename: <?php echo $filepath; ?></p>
    <p>Line Number: <?php echo $line; ?></p>
</body>
</html>
