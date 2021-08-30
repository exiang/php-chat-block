<?php
include 'ChatUIClass.php';
$sample = file_get_contents('./sample.txt');
?>


<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Text soap</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="imessage.css">
    </head>
    <body>
        <div class="container" id="chatUI">
        <h1>Text Soap</h1>
        <?php
        $dialogue = new ChatUI($sample);
        echo $dialogue->read();
        ?>
        </div>
    </body>
</html>