<?php require_once('dbConfig.php');
class functions extends dbConfig
{
  function __construct()
  {
	parent::__construct();   
  }
  function __destruct()
  {
	parent::__destruct();  
  }	

function registerPlayers($playerX="", $playerO="")   // Create Players
{  
    if($playerX != '' && $playerO != '')
    {
        if($this->ExNonQuery("INSERT INTO user_info(player1,player2) VALUES ('".$playerX."','".$playerO."')"))
        {
            $LastID=mysqli_insert_id($this->con);

            $_SESSION['PLAYER_X_NAME'] = $playerX;
            $_SESSION['PLAYER_O_NAME'] = $playerO;
            $_SESSION['gameID'] = $LastID;
            $this->setTurn('x');
            $this->resetBoard();
            $this->resetWins();
        }
    }   
}

function resetBoard() {   // to reset the whole board
    $this->resetPlaysCount();
    for ( $i = 1; $i <= 9; $i++ ) {
        unset($_SESSION['CELL_' . $i]);
    }
}

function resetWins() {   // to reset player / win players
    $_SESSION['PLAYER_X_WINS'] = 0;
    $_SESSION['PLAYER_O_WINS'] = 0;
}

function playsCount() {  // to count the plays which players play
    return $_SESSION['PLAYS'] ? $_SESSION['PLAYS'] : 0;
}

function addPlaysCount() {  // increment the play counts
    if (! $_SESSION['PLAYS']) {
        $_SESSION['PLAYS'] = 0;
    }
    $_SESSION['PLAYS']++;
}

function resetPlaysCount() { // reset the count of plays
    $_SESSION['PLAYS'] = 0;
}

function playerName($player='x') {  // fetch player name 
    return $_SESSION['PLAYER_' . strtoupper($player) . '_NAME'];

}

function playersRegistered() {   // fetch players that are in session
    return $_SESSION['PLAYER_X_NAME'] && $_SESSION['PLAYER_O_NAME'];
}

function setTurn($turn='x') {   // set the turn i.e. x / o 
    $_SESSION['TURN'] = $turn;
}

function getTurn() {
    return $_SESSION['TURN'] ? $_SESSION['TURN'] : 'x';    // get the turn which is store in session
}

function markWin($player='x') {

    $this->ExNonQuery("UPDATE moves_details SET win_outcome = 1  WHERE  gameID = '".$_SESSION['gameID']."' AND playerName = '".$_SESSION['PLAYER_' . strtoupper($player) . '_NAME']."' ");       // update the winner as 1, whichever player is win.

    $_SESSION['PLAYER_' . strtoupper($player) . '_WINS']++;
}

function switchTurn() {    // set the turn using switchcase
    switch ($this->getTurn()) {
        case 'x':
            $this->setTurn('o');
            break;
        default:
        $this->setTurn('x');
            break;
    }
}

function currentPlayer() {
    return $this->playerName($this->getTurn());    // get the current player name
}

function play($cell='') {
    
    if ($this->getCell($cell)) {
        return false;
    }

    $_SESSION['CELL_' . $cell] = $this->getTurn();

    if($this->getTurn() == 'x'){ $movesPlayerArr['x'][] = $cell; }
    else{  $movesPlayerArr['o'][] = $cell;  }

    $this->addPlaysCount();
    
    $win = $this->playerPlayWin($cell);

    if (! $win) {
        $this->switchTurn();

        $arr = $this->Fetch_Single("SELECT * FROM moves_details WHERE gameID = '".$_SESSION['gameID']."' AND playerName = '".$_SESSION['PLAYER_' . strtoupper($_SESSION['CELL_' . $cell]) . '_NAME']."' " );   // check the data is in table or not
        $Allmove = array();
        if(isset($arr))
        {
            // data is exist update the moves
            $Allmove[] = $arr['moves']  ;
            array_push($Allmove,$cell);

            $this->ExNonQuery("UPDATE moves_details SET moves = '".implode(',',$Allmove)."' WHERE  gameID = '".$_SESSION['gameID']."' AND playerName = '".$_SESSION['PLAYER_' . strtoupper($_SESSION['CELL_' . $cell]) . '_NAME']."' ");
        }
        else
        {
             // data is not exist insert the moves
            $this->ExNonQuery("INSERT INTO moves_details (gameID,playerName,moves) VALUES ('".$_SESSION['gameID']."','".$_SESSION['PLAYER_' . strtoupper($_SESSION['CELL_' . $cell]) . '_NAME']."','".$cell."')");
        }
    }
    else {
        $this->markWin($this->getTurn());  // if win update the win player in database
        $this->resetBoard();   // reset the board
    }

    return $win;
}

function getCell($cell='') {
    return $_SESSION['CELL_' . $cell];    // store the cell value in session
}

function playerPlayWin($cell=1) {
    if ($this->playsCount() < 3) {
        return false;
    }

    $column = $cell % 3;
    if (! $column) {
        $column = 3;
    }

    $row = ceil($cell / 3);

    $player = $this->getTurn();

    return $this->isVerticalWin($column, $player) || $this->isHorizontalWin($row, $player) || $this->isDiagonalWin($player);
}

function isVerticalWin($column=1, $turn='x') {
    return $this->getCell($column) == $turn &&
    $this->getCell($column + 3) == $turn &&
    $this->getCell($column + 6) == $turn;
}

function isHorizontalWin($row=1, $turn='x') {
    return $this->getCell($row) == $turn &&
    $this->getCell($row + 1) == $turn &&
    $this->getCell($row + 2) == $turn;
}

function isDiagonalWin($turn='x') {
    $win = $this->getCell(1) == $turn &&
    $this->getCell(9) == $turn;

    if (! $win) {
        $win = $this->getCell(3) == $turn &&
        $this->getCell(7) == $turn;
    }

    return $win && $this->getCell(5) == $turn;
}

function score($player='x') {
    $score = $_SESSION['PLAYER_' . strtoupper($player) . '_WINS'];   // store the score 
    return $score ? $score : 0;
}

} 
$commonobj=new functions();
?>