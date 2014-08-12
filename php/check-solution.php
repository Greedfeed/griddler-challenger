<?php

	if($_POST)
	{
		$solution 	= unserialize(base64_decode($_POST['board_solution']));
		$answer 	= json_decode($_POST['board_answer'], true);

		print_r($answer);
		print_r($solution);
		

		//$decrypted_board = unserialize(base64_decode($_POST['solution']));
		//print_r( $decrypted_board );

		/*$array2 = array( 
				'1' => array( 
					'1' => 1, 
					'2' => 0, 
					'3' => 0, 
					'4' => 0, 
					'5' => 0 
					),
				'2' => array( 
					'1' => 0, 
					'2' => 0, 
					'3' => 0, 
					'4' => 0, 
					'5' => 1 
					),
				'3' => array( 
					'1' => 0, 
					'2' => 0, 
					'3' => 0, 
					'4' => 0, 
					'5' => 0 
					),
				'4' => array( 
					'1' => 1, 
					'2' => 0, 
					'3' => 0, 
					'4' => 0, 
					'5' => 1 
					),
				'5' => array( 
					'1' => 0, 
					'2' => 0, 
					'3' => 0, 
					'4' => 0, 
					'5' => 0 
					) 
				);
		*/
	}

?>