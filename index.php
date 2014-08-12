<html>
<head>
	<style>
		* {
			font-family: monospace;
		}
		.tiles:hover {
			opacity:.5;
		}
	</style>
</head>

<body>
<?php

	/***USE THE FOLLOWING TO DECODE
		$decrypted_board = unserialize(base64_decode($encrypted_board));
	*/
	
	$columns 	= 5;
	$rows 		= 5;
	
	$board = array();

	for($i=1; $i<=$columns; $i++) {

		for($j=1; $j<=$rows; $j++) {
			$hit_roll = rand(1, 3);

			if($hit_roll > 2) {
				$board[$i][$j] = 1;
			}
			else {
				$board[$i][$j] = 0;
			}
			//echo $board[$i][$j];
		}
		//echo '<br />';
	}

	//Encrypting the board to be stored locally without giving away the solution
	$encrypted_board = base64_encode(serialize($board));

	$row_curr 	= 	1;
	$row_total 	=	0;
	$row_count 	= 	array();

	for($j=1; $j <= $columns; $j++){
		for($i=1; $i <= $rows; $i++) {
			if ($board[$j][$i] == 1) {
				$row_total = $row_total + 1;
				$row_count[$j][$row_curr] = $row_total;

				// If you are on the last row, increase the row count by 1 and reset the row total to zero
				if($rows == $i) {
					$row_total = 0;
					$row_curr = $row_curr + 1;
				}
			}
			else {
				//If the row count hasn't yet created and we're on the last row, set it
				if ( !isset($row_count[$j]) && $rows == $i ) {
					$row_count[$j][$row_curr] = 0;
					$row_total = 0;
				}
				//if the row total has a number greater than zero, reset it and move on to the next
				elseif ($row_total > 0) {
					$row_total = 0;
					$row_curr = $row_curr + 1;
				}
			}
		}
		$row_curr = 1;
	}
	//print_r($row_count);

	
	$column_curr = 1;
	$column_total =	0;
	$column_count = array();

	for($j=1; $j <= $rows; $j++) {
		for($i=1; $i <= $columns; $i++) {
			if ($board[$i][$j] == 1) {
				$column_total = $column_total + 1;
				$column_count[$j][$column_curr] = $column_total;

				// If you are on the last column, increase the column count by 1 and reset the column total to zero
				if($columns == $i) {
					$column_total 	= 0;
					$column_curr 	= $column_curr + 1;
				}
			}
			else {
				//If the column count hasn't yet created and we're on the last column, set it
				if ( !isset($column_count[$j]) && $columns == $i ) {
					$column_count[$j][$column_curr] = 0;
					$column_total = 0;
				}
				//if the column total has a number greater than zero, reset it and move on to the next
				elseif ($column_total > 0)
				{
					$column_total = 0;
					$column_curr = $column_curr + 1;
				}
			}
		}
		$column_curr = 1;
	}
	//print_r($column_count);
?>
	<div id="board_container"></div>
	
	<form id="board_answer" action="" method="POST">
		<input name="board_solution" id="board_solution" type="hidden" value="<?=$encrypted_board?>" />
		<input id="board_submit" type="submit" />
	</form>

</body>
<script>
	var rows			= <?=$rows;?>;
	var columns			= <?=$columns;?>;
	var row_count		= <?=json_encode($row_count)?>;
	var column_count	= <?=json_encode($column_count)?>;
</script>

<script src="js/griddler.js"></script>
</html>