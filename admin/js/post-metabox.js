jQuery(document).ready( function($) {

	function isChecked (checklist, id) {
		return checklist ? checklist.search(id) >= 0 : false;
	}

	function createFeatureList(groupId, features, values) {
		var markup = '<table>',
			feature,
			checked,
			id,
			name,
			value;

		for (var j = 0; j < features.length; j++) {
			feature = features[j];
			id = 'efl-feature-' + groupId + '-' + j;
			name = 'efl-feature[' + groupId + '][' + j + ']';
			value = (values[groupId] && values[groupId][j]) ? values[groupId][j] : '';

			markup += '<tr><td>' + feature.name + ':</td>';
			if (feature.type == 'Checkbox') {
				markup += '<td><input type="checkbox" value="checked" name="' + name + '" id="' + id + '"' + value + '/></td></tr>';
			} else if (feature.type == 'Text') {
				markup += '<td><input type="text" name="' + name + '" id="' + id + '" value="' + value + '"/></td></tr>';
			}

		}
		markup += '</table>';
		return markup;
	}

	function getPostID () {
	    var idx = window.location.href.search("post=");
	    if (idx) {
	    	return window.location.href.substring(idx).split('&')[0].split('=')[1];
	    } else {
	    	return undefined;
	    }
	}

	function getFeatureList () {
		var postData = {
			'action': 'efl_ajax_get_feature_list',
			'feature_list_id': $('#efl-list-sel').val(),
			'post_id': getPostID()
		};

		$.post(EFL_AJAX_URL, postData, function(response) {
			var features,
				values,
				table,
				group,
				row;

			response = JSON.parse(response);
			features = response.features !== "" ? JSON.parse(response.features) : [];
			values = response.values !== "" ? JSON.parse(response.values) : [];

			$('#efl-fl-table').remove();
			$('#efl-fl-hr').remove();
			$('#efl-mb-table').after($('<table class="form-table" id="efl-fl-table"></table>')).after($('<hr id="efl-fl-hr"/>'));
			table = $('#efl-fl-table');

			for (var i = 0; i < features.length; i++) {
				group = features[i];
				row = $('<tr valign="top"></tr>').appendTo(table);
				row.append('<th scope="row">' + group.name + '</th>');
				row.append('<td>' + createFeatureList(i, group.features, values) + '</td>');
			}
		});
	}

	if (window.location.href.search('post.php?') >= 0 || window.location.href.search('post-new.php?') >= 0) {
		$('#efl-list-sel').change(getFeatureList);
		getFeatureList();		
	}
});