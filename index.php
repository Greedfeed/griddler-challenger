<html>
<head>
</head>

<body>


<?php
	
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
			echo $board[$i][$j];
		}
		echo '<br />';
	}

	echo '<pre>';
	//print_r($board);
	
	$column_curr = 1;
	$column_total =	0;
	$column_count = array();

	for($j=1; $j <= $rows; $j++) {
		for($i=1; $i <= $columns; $i++) {
			if ($board[$i][$j] == 1) {
				$column_total = $column_total + 1;
				$column_count[$j][$column_curr] = $column_total;
			}
			else {
				if ($column_total > 0)
				{
					$column_total = 0;
					$column_curr = $column_curr + 1;
				}
			}
		}

		$column_curr = 1;
	}

	print_r($column_count);


	$row_curr = 1;
	$row_total =	0;
	$row_count = array();

	for($j=1; $j <= $columns; $j++){
		for($i=1; $i <= $rows; $i++) {
			if ($board[$j][$i] == 1) {
				$row_total = $row_total + 1;
				$row_count[$j][$row_curr] = $row_total;
			}
			else {
				if ($row_total > 0)
				{
					$row_total = 0;
					$row_curr = $row_curr + 1;
				}
			}
		}
		
		$row_curr = 1;
	}

	print_r($row_count);

?>


</body>
</html>