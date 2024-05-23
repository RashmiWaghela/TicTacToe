<?php
require_once "templates/header.php";

if (! $commonobj->playersRegistered()) {
    header("location: index.php");
}

$commonobj->resetBoard();
?>

<table class="wrapper" cellpadding="0" cellspacing="0">
    <tr>
        <td>

            <div class="welcome">

                <h1>
                    <?php
                    if ($_GET['player']) {
                        echo $commonobj->currentPlayer() . " won!";
                    }
                    else {
                        echo "It's a tie!";
                    }
                    ?>
                </h1>

                <div class="player-name">
                    <?php echo $commonobj->playerName('x')?>'s score: <b><?php echo $commonobj->score('x')?></b>
                </div>

                <div class="player-name">
                    <?php echo $commonobj->playerName('o')?>' score: <b><?php echo $commonobj->score('o')?></b>
                </div>

                <a href="play.php?type=replay">Play again</a><br />

                <a href="index.php" class="reset-btn">Reset</a>
            </div>

        </td>
    </tr>
</table>

</body>
</html>

