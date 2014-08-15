<html>
<head>
	<style>
		* {
			font-family: monospace;
		}
		.tiles:hover {
			opacity:.5;
		}
		#tool_img:hover {
			opacity:.5;
		}

	</style>
</head>

<body>
<?php

	/***USE THE FOLLOWING TO DECODE
		$decrypted_board = unserialize(base64_decode($encrypted_board));
	*/
	
	$total_columns	= isset($_POST['columns'])	? $_POST['columns']	:	3;
	$total_rows		= isset($_POST['rows'])		? $_POST['rows']	:	3;
	
	$board = array();

	for($i=1; $i<=$total_rows; $i++) {

		for($j=1; $j<=$total_columns; $j++) {
			$hit_roll = rand(1, 8);

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

	//I REALLY DO NOT UNDERSTAND MATH AND FORMULAS ENOUGH TO KNOW HOW I GOT THIS TO WORK AS TO WHY THIS WORKS
	if ($total_columns == $total_rows) {
		$row_count 		= hit_counter($board, 	$total_columns, $total_rows);
		$column_count 	= hit_counter($board,	$total_columns,	$total_rows, 'flip');
	}
	else {
		$row_count 		= hit_counter($board, 	$total_rows, 	$total_columns, 'flip');
		$column_count 	= hit_counter($board,	$total_columns,	$total_rows);
	}
	
	/***
		Sorts through two arrays and determines where the 'hits' are located in order to send the clues to the user without the answer
		This function was developer using the dark arts and a lot of trial and error... but mostly by using the dark arts
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
	<p>All boxes must be filled in or removed to count as a correct answer</p>

	<div>
		<p>Current mode:
			<div id="toolset"><a id="tool" href="javascript:void(0);" onclick="toggle_mode('hammer');"><img id="tool_img" src="img/brush.gif" /></a></div>
		</p>
	</div>

	<div id="board_container"></div>

	<form id="board_answer" action="" method="POST">
		<input name="board_solution" id="board_solution" type="hidden" value="<?=$encrypted_board?>" />
		<input id="answer_submit" type="submit" value="Check Answer" />
	</form>

	<form id="create_board" action="" method="POST">
		<select name="rows">
			<option  value="5">	 5x5</option>
			<option value="10">10x10</option>
			<option value="15">15x15</option>
			<option value="20">20x20</option>
			<option value="25">25x25</option>
		</select>
		<input id="create_submit" type="submit" value="Create Board" />
	</form>

</body>
<script>
	var rows			= <?=$total_rows;?>;
	var columns			= <?=$total_columns;?>;
	var column_count	= <?=json_encode($column_count)?>;
	var row_count		= <?=json_encode($row_count)?>;
	var current_mode	= 'brush';
</script>

<script src="js/griddler.js"></script>
</html>