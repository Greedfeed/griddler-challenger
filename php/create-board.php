<?php
	
	$total_columns	= isset($_POST['rows_columns'])		? $_POST['rows_columns']	:	5;
	$total_rows		= isset($_POST['rows_columns'])		? $_POST['rows_columns']	:	5;
	$difficulty		= isset($_POST['board_difficulty'])	? $_POST['board_difficulty']:	6;

	
	$board = array();

	for($i=1; $i<=$total_rows; $i++) {

		for($j=1; $j<=$total_columns; $j++) {
			$hit_roll = rand(1, $difficulty);

			if($hit_roll > 2) {
				$board[$j][$i] = 1;
			}
			else {
				$board[$j][$i] = 0;
			}
		}
	}

	//Encrypting the board to be stored locally without giving away the solution
	$encrypted_board = base64_encode(serialize($board));

	//I REALLY DO NOT UNDERSTAND MATH AND FORMULAS ENOUGH TO KNOW HOW I GOT THIS TO WORK OR EVEN AS TO WHY THIS WORKS
	if ($total_columns == $total_rows) {
		$row_count 		= hit_counter($board, 	$total_columns, $total_rows);
		$column_count 	= hit_counter($board,	$total_columns,	$total_rows, 'flip');
	}
	else {
		$row_count 		= hit_counter($board, 	$total_rows, 	$total_columns, 'flip');
		$column_count 	= hit_counter($board,	$total_columns,	$total_rows);
	}
	
	/***
		Sorts through two arrays and determines where the 'hits' are located in order to send the clues to the user without the unecrypted answer
		This function was developed using the dark arts and a lot of trial and error... but mostly by using the dark arts
	*/
	function hit_counter($board, $outer_loop, $inner_loop, $type = FALSE) {
		$current_set 	= 	1;
		$consecutive_hits	= 0;
		$total_hits 	= 	array();

		for($j=1; $j <= $outer_loop; $j++){
			for($i=1; $i <= $inner_loop; $i++) {
				if ($type == 'flip') {
					$board_value =	$board[$i][$j];
				}
				else {
					$board_value =	$board[$j][$i];
				}

				//If we found a hit in the array...
				if ($board_value == 1 ) {
					$consecutive_hits = $consecutive_hits + 1;
					$total_hits[$j][$current_set] = $consecutive_hits;

					// If you are on the last inner loop, increase the set count by 1 and reset the consecutive hits to zero
					if($inner_loop == $i) {
						$consecutive_hits = 0;
						$current_set = $current_set + 1;
					}
				}
				else {
					//If the inner loop count hasn't been created yet and we're on the last row, set it to 0s
					if ( !isset($total_hits[$j]) && $inner_loop == $i ) {
						$total_hits[$j][$current_set] = 0;
						$consecutive_hits = 0;
					}
					//If the consecutive hits has a number greater than zero, reset it and move on to the next set
					elseif ($consecutive_hits > 0) {
						$consecutive_hits = 0;
						$current_set = $current_set + 1;
					}
				}
			}
			$current_set = 1;
		}

		return $total_hits;
	}
?>