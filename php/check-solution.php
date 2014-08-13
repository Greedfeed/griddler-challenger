<?php

	if($_POST)
	{
		$response = array();

		$solution 	= unserialize(base64_decode($_POST['board_solution']));
		$answer 	= json_decode($_POST['board_answer'], true);

		//We need to make sure the answer and the solution are atleast the same length, or we're going to have a bad time
		if (count($answer) == count($solution)) {
			
			for ($i=1; $i <= count($answer);$i++) {
				//We need to make sure the answer and the solution are atleast the same length
				if (count($answer[$i]) == count($solution[$i])) {
					for ($j=1; $j <= count($answer[$i]);$j++) {
						if ($answer[$i][$j] != $solution[$i][$j]) {
							$response[] = array(
									'row'		=> $i,
									'column'	=> $j
								);
						}
					}
				}
			}
		}
		echo json_encode($response);
	}
?>