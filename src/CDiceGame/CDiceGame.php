<?php

class CDiceGame {
	private $player1Points;
	private $player2Points;
	
	private $player1PointsCurrentRound;
	private $player2PointsCurrentRound;
	
	private $player1Winner;
	private $player2Winner;
	
	private $turn;
	
	public function __construct() {
		$this->player1Points = 0;
		$this->player2Points = 0;
		
		$this->player1PointsCurrentRound = 0;
		$this->player2PointsCurrentRound = 0;
		
		$this->player1Winner = false;
		$this->player2Winner = false;
		
		$this->turn = "player1";
	}
	
	public function roll() {
		$roll = rand(1,6);
		if ($this->turn == "player1") {
			if ($roll == 1) {
				$this->player1PointsCurrentRound = 0;
				$this->newRound();
			} else {
				$this->player1PointsCurrentRound += $roll;
			}
		}
		return $roll;
	}
	
	public function newRound() {
		$this->player1Points += $this->player1PointsCurrentRound;
		$this->player1PointsCurrentRound = 0;
		$this->turn = "player2";
		for ($i = 0; $i < 3; $i++) {
			$roll = $this->roll();
			if ($roll == 1) {
				$this->player2PointsCurrentRound = 0;
				break;
			}
			$this->player2PointsCurrentRound += $roll;
			
		}
		$this->player2Points += $this->player2PointsCurrentRound;
		$this->player2PointsCurrentRound = 0;
		$this->turn = "player1";
		
		$this->checkWinner();
	}
	
	public function checkWinner() {
		if ($this->player1Points >= 100) {
			$this->player1Winner = true;
		}
		if ($this->player2Points >= 100) {
			$this->player2Winner = true;
		}
	}
	
	public function getOutput() {
		$output = null;
		if (!$this->player1Winner && !$this->player2Winner) {
			$output = "<table>
				<tr>
					<td></td>
					<td>DU</td>
					<td>DATOR</td>
				</tr>
				<tr>
					<td>Totalpoäng</td>
					<td>{$this->player1Points}</td>
					<td>{$this->player2Points}</td>
				</tr>
				<tr>
					<td>Runda</td>
					<td>{$this->player1PointsCurrentRound}</td>
					<td>{$this->player2PointsCurrentRound}</td>
				</tr>
				<tr>
					<td></td>
					<td><a href='?action=roll'>Roll</a></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td><a href='?action=roundcomplete'>Klar</a></td>
					<td></td>
				</tr>
			</table>
			<p><a href='?action=start'>Starta om</a></p>
			<p><a href='?action=quit'>Avsluta spelet</a></p>";
		} else {
			if ($this->player2Winner) {
				$output = "<p>Dator vann.</p>";
				$output .= "<p><a href='?action=start'>Spela igen!</a></p>";
				$output .= "<p><a href='?action=quit'>Avsluta spelet</a></p>";
			}
		}
		
		return $output;
	}
	
	public function player1Winner() {
		$result = false;
		if ($this->player1Winner) {
			$result = true;
		}
		return $result;
	}
}