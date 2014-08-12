create_layout(rows, columns);


document.getElementById('board_submit').onclick = function() {
	event.preventDefault();

	var board_div = document.getElementById('board');
	var board_answer = {};
	

	for (var i = 0; i < board_div.childNodes.length; i++) {
		var selected_row = document.getElementById('row_'+i);
		
		board_answer[i+1] = {};

		for (var j = 0; j < selected_row.childNodes.length; j++) {
			var selected_tile = document.getElementById('tile_'+i+'_'+j);
			var	tile_answer = selected_tile.classList.contains('selected') ? 1 : 0;

			board_answer[i+1][j+1] = tile_answer;
		}
	}

	var stringy_board_answer = JSON.stringify( board_answer );
	var board_solution = document.getElementById('board_solution').value;

	board_post_data = 'board_answer='+stringy_board_answer+'&board_solution='+board_solution;

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 ) {
			if (xmlhttp.status==200) {
				response = xmlhttp.responseText;

				console.log( response );
			}
			else  {
				alert('You need at least Internet Explorer 8 or better to roll dice. Embrace change you luddite.');
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
			table_html += '<td id="hint_col_'+i+'">';
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
			table_html += '<td>';
			for (var j=1; j<=Object.keys(row_count[i]).length; j++) {
				if(row_count[i][j] != 0) {
					table_html += row_count[i][j];
				}
				else {
					table_html += '&nbsp;';
				}
			}
			table_html += '</tr>';
		table_html += '</td>';
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
	board_html += '<div id="board">';

	for (var i=0; i < rows; i++) {
		board_html += '<div id="row_'+i+'" class="row">';

		for (var j=0; j < columns; j++) {
			board_html += '<span id="space_'+i+'_'+j+'">';
				board_html += '<img id="tile_'+i+'_'+j+'" class="tiles default" src="img/tile-set/default-tile.gif"  onclick="select_tile('+i+','+j+',\'selected\');"/>';
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
function select_tile(row, column, type) {
	opposite_type = type == 'default' ? 'selected' : 'default';

	var selected_tile = document.getElementById('tile_'+row+'_'+column);
	selected_tile.parentNode.removeChild(selected_tile);
	var tile_html = '<img id="tile_'+row+'_'+column+'" class="tiles '+type+'" src="img/tile-set/'+type+'-tile.gif"  onclick="select_tile('+row+','+column+',\''+opposite_type+'\');"/>';
	document.getElementById('space_'+row+'_'+column).innerHTML = document.getElementById('space_'+row+'_'+column).innerHTML + tile_html;
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