<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Exception</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>An uncaught Exception was encountered</h1>

    <p>Type: <?php echo get_class($exception); ?></p>
    <p>Message: <?php echo $message; ?></p>
    <p>Filename: <?php echo $exception->getFile(); ?></p>
    <p>Line Number: <?php echo $exception->getLine(); ?></p>

    <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
        <p>Backtrace:</p>
        <?php foreach ($exception->getTrace() as $error): ?>
            <?php if (isset($error['file'])): ?>
                <p style="margin-left:10px">
                File: <?php echo $error['file']; ?><br>
                Line: <?php echo $error['line']; ?><br>
                Function: <?php echo $error['function']; ?>
                </p>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
</body>
</html>
