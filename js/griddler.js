create_layout(rows, columns);
	
    
document.getElementById('answer_submit').addEventListener(
    'click', check_answer, false
);

function check_answer(event) {
	event.preventDefault();

	var board_div = document.getElementById('board');
	var board_answer = {};
	var tiles_class = document.getElementsByClassName('tiles');
	var total_wrong = 0;

	for (var i = 1; i <= board_div.childNodes.length; i++) {
		var selected_row = document.getElementById('row_'+i);
		
		board_answer[i] = {};

		for (var j = 1; j <= selected_row.childNodes.length; j++) {
			var selected_tile = document.getElementById('tile_'+i+'_'+j);
			var	tile_answer = selected_tile.classList.contains('selected') ? 1 : 0;
			if (selected_tile.classList.contains('default') ) {
					select_tile(i, j, 'incorrect', event);
			}

			board_answer[i][j] = tile_answer;
		}
	}

	var stringy_board_answer = JSON.stringify( board_answer );
	var board_solution = document.getElementById('board_solution').value;

	board_post_data = 'board_answer='+stringy_board_answer+'&board_solution='+board_solution;

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 ) {
			if (xmlhttp.status==200) {
				var response = JSON.parse(xmlhttp.responseText);

				for(var i=0;i<response.length;i++) {
					var row 	= response[i].row;
					var column 	= response[i].column;
					select_tile(row, column, 'incorrect', event);
				}

				total_wrong	= document.getElementsByClassName('incorrect').length;
				
				if (total_wrong > 0) {
					alert('dis many wrong: '+ total_wrong);
				}
				else {
					alert('dis many wrong: '+ total_wrong);
				}
			}
			else  {
				alert('You need at least Internet Explorer 8 or better to solve this puzzle. Embrace change you luddite.');
			}
		}
	}
	xmlhttp.open('POST','php/check-solution.php',true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	xmlhttp.send(board_post_data);
}


/**
	creates the layout along with the hints before moving onto placing the board
*/
function create_layout(rows, columns) {
	var table_html = '<table border="1" style="text-align:center;">';
	table_html += '<tr id="hint_row_0">';
		table_html += '<td>&nbsp;</td>';

		for (var i=1; i<=Object.keys(column_count).length; i++) {
			table_html += '<td id="hint_col_'+i+'" class="hint_col">';
			for (var j=1; j<=Object.keys(column_count[i]).length; j++) {
				if(column_count[i][j] != 0) {
					table_html += column_count[i][j] + '<br />';
				}
				else {
					table_html += '&nbsp;';
				}
			}
			table_html += '</td>';
		}
		
	table_html += '</tr>';

	for (var i=1; i<=Object.keys(row_count).length; i++) {
		table_html += '<tr id="hint_row_'+i+'">';
			table_html += '<td class="hint_row">';
			for (var j=1; j<=Object.keys(row_count[i]).length; j++) {
				if(row_count[i][j] != 0) {
					table_html += row_count[i][j];
				}
				else {
					table_html += '&nbsp;';
				}
			}
			table_html += '</td>';
		table_html += '</tr>';
	}
	table_html += '</table>';

	document.getElementById('board_container').innerHTML = table_html;
	
	create_board(rows, columns);
}


/**
	creates the board based on the tile set
*/
function create_board(rows, columns) {

	var board_html = '<td rowspan="'+rows+'" colspan="'+columns+'">';
	board_html += '<div id="board" oncontextmenu="return false;">';

	for (var i=1; i <= rows; i++) {
		board_html += '<div id="row_'+i+'" class="row">';

		for (var j=1; j <= columns; j++) {
			board_html += '<span id="space_'+i+'_'+j+'">';
				board_html += '<img id="tile_'+i+'_'+j+'" class="tiles default" src="img/tile-set/default-tile.gif" onmousedown="select_tile('+i+','+j+',\'selected\', event);"/>';
			board_html += '</span>';
		}

		board_html += '</div>';
	}
	board_html += '</div>';

	document.getElementById('hint_row_'+1).innerHTML = document.getElementById('hint_row_'+1).innerHTML + board_html;
}



/**
	selects the tile to in the puzzle
*/
function select_tile(row, column, type, click_event) {
	var selected_tile = document.getElementById('tile_'+row+'_'+column);
	var opposite_type = type == 'default' ? 'selected' : 'default';
	var click_event = click_event || window.event;

	//if right click was used, toggle the results
	if ((click_event.which && click_event.which == 3) || (click_event.button && click_event.button == 2)) {
		toggle_mode();
	}

	//only apply special rules if we aren't marking a tile incorrect
	if (type != 'incorrect') {
		if (current_mode == 'hammer') {
			var type = type == 'selected' ? 'removed' : 'default';
			if (current_mode == 'hammer' && selected_tile.classList.contains('selected')) {
				type = 'removed';
				opposite_type = 'default'; 
			}
		}

		if (current_mode == 'brush' && selected_tile.classList.contains('removed')) {
			type = 'selected';
			opposite_type = 'default'; 
		}
	}

	//if right click was used, toggle the results back
	if ((click_event.which && click_event.which == 3) || (click_event.button && click_event.button == 2)) {
		toggle_mode();
	}

	selected_tile.parentNode.removeChild(selected_tile);
	var tile_html = '<img id="tile_'+row+'_'+column+'" class="tiles '+type+'" src="img/tile-set/'+type+'-tile.gif"  onmousedown="select_tile('+row+','+column+',\''+opposite_type+'\', event);"/>';
	document.getElementById('space_'+row+'_'+column).innerHTML = document.getElementById('space_'+row+'_'+column).innerHTML + tile_html;
	return false;
}


/**
	basic function to replicate jQuery's serialize
*/
function serialize(form_id) {
	var form_string = [];
	
	for ( var i = 0; i < form_id.elements.length; i++ ) {
		var e = form_id.elements[i];
		if(e.name != ''){
			form_string.push(encodeURIComponent(e.name) + '=' + encodeURIComponent(e.value));
		}
	}

	var data = form_string.join('&');
	return data;
}

/**
	this will toggle between the paint mode and the hammer mode
*/
function toggle_mode(mode) {

	if (typeof mode !== 'undefined') {
		var mode = mode;
	}
	else  {
		if (current_mode == 'brush') {
			mode = 'hammer';
		}
		else {
			mode = 'brush';
		}
	}

	var tool 	= document.getElementById('tool');
	var toolset = document.getElementById('toolset');

	if (mode == 'hammer') {
		tool_html = '<a id="tool" href="javascript:void(0);" onclick="toggle_mode(\'brush\');"><img id="tool_img" src="img/hammer.gif" /></a>';
		current_mode =	'hammer';
	}
	else {
		tool_html = '<a id="tool" href="javascript:void(0);" onclick="toggle_mode(\'hammer\');"><img id="tool_img" src="img/brush.gif" /></a>';
		current_mode =	'brush';
	}

	toolset.innerHTML = tool_html;

}
