<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	<style>
		* {
			font-family: Arial, "Courier New", Courier, monospace;
			font-size:14px;
		}

		body {
			position: relative;
			color: #333;
			line-height: 1.4;
		}

		#container {
			width: 95%;
			max-width: 58.750em;
			margin: 0 auto;
			margin-bottom: 0;
		}

		.tiles:hover, #tool_img:hover {
			opacity:.5;
		}

	</style>

	<?php require_once('php/create-board.php'); ?>
</head>

<body>
	<div id="container">
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
			<select name="rows_columns">
				<option  value="5">	 5x5</option>
				<option value="10">10x10</option>
				<option value="15">15x15</option>
				<option value="20">20x20</option>
				<option value="25">25x25</option>
			</select>
			<select name="board_difficulty">
				<option value="10">Very Easy</option>
				<option value="8">Easy</option>
				<option value="6" selected="selected">Moderate</option>
				<option value="4">Challenging</option>
				<option value="3">Impossible</option>
			</select>

			<input id="create_submit" type="submit" value="Create Board" />
		</form>
	</div>

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