create_layout(rows, columns);

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
			board_html += '<img id="tile_'+i+'_'+j+'" class="tiles" src="img/tile-set/default-tile.gif"  onclick="select_tile('+i+','+j+');"/>';
		}

		board_html += '</div>';
	}
	board_html += '</div>';

	document.getElementById('hint_row_'+1).innerHTML = document.getElementById('hint_row_'+1).innerHTML + board_html;
}