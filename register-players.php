<?php require_once "functions.php";
$commonobj->registerPlayers($_POST['player-x'], $_POST['player-o']);

if ($commonobj->playersRegistered()) {
    header("location: play.php");
}
?>