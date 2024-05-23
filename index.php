<?php
require_once "./templates/header.php";
?>

<form method="post" action="register-players.php">
    <div class="welcome">
        <h1>Start Tic Tac Toe!</h1>
        <h2>Please fill below details</h2>

        <div class="player-name">
            <label for="player-x">First Player (X)</label>
            <input type="text" id="player-x" name="player-x" class="form-control" required />
        </div>

        <div class="player-name">
            <label for="player-o">Second Player (O)</label>
            <input type="text" id="player-o" name="player-o" class="form-control" required />
        </div>

        <button type="submit " class="btn btn-lg btn-primary">Start</button>
    </div>
</form>

<?php require_once "./templates/footer.php"; ?>  