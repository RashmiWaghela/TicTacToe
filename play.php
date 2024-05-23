<?php
require_once "templates/header.php";

if (! $commonobj->playersRegistered()) {
    header("location: index.php");
}

if($_GET['type'] == 'replay')
{
    $commonobj->registerPlayers($_SESSION['PLAYER_X_NAME'],$_SESSION['PLAYER_O_NAME']);
}

if ($_POST['cell']) {
    $win = $commonobj->play($_POST['cell']);

    if ($win) {
        header("location: result.php?player=" . $commonobj->getTurn());
    }
}

if ($commonobj->playsCount() >= 9) {
    header("location: result.php");
}
?>

<h2><?php echo $commonobj->currentPlayer() ?>'s turn</h2>

<form method="post" action="play.php">

    <table class="tic-tac-toe" cellpadding="0" cellspacing="0">
        <tbody>

        <?php
        $lastRow = 0;
        for ($i = 1; $i <= 9; $i++) {
            $row = ceil($i / 3);

            if ($row !== $lastRow) {
                $lastRow = $row;

                if ($i > 1) {
                    echo "</tr>";
                }

                echo "<tr class='row-{$row}'>";
            }

            $additionalClass = '';

            if ($i == 2 || $i == 8) {
                $additionalClass = 'vertical-border';
            }
            else if ($i == 4 || $i == 6) {
                $additionalClass = 'horizontal-border';
            }
            else if ($i == 5) {
                $additionalClass = 'center-border';
            }
            ?>

            <td class="cell-<?= $i ?> <?= $additionalClass ?>">
                <?php if ($commonobj->getCell($i) === 'x'): ?>
                    X
                <?php elseif ($commonobj->getCell($i) === 'o'): ?>
                    O
                <?php else: ?>
                    <input type="radio" name="cell" value="<?= $i ?>" onclick="enableButton()"/>
                <?php endif; ?>
            </td>

        <?php } ?>

        </tr>
        </tbody>
    </table>

    <button type="submit" disabled id="play-btn">Play</button>

</form>

<script type="text/javascript">
    function enableButton() {
        document.getElementById('play-btn').disabled = false;
    }
</script>

<?php require_once "templates/footer.php"; ?>