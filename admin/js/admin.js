jQuery(document).ready( function($) {

	function isChecked (checklist, id) {
		return checklist ? checklist.search(id) >= 0 : false;
	}

	function createFeatureList(id, features, checklist) {
		var markup = '',
			checked,
			checkId,
			checkName;

		for (var j = 0; j < features.length; j++) {
			checkId = 'efl-chk-' + id + '-' + j;
			checkName = 'efl-chk[' + id + '][' + j + ']';
			checked = isChecked(checklist, id + '-' + j) ? ' checked ' : '';

			markup += '<input type="checkbox" value="checked" name="' + checkName + '" id="' + checkId + '"' + checked + '/>';
			markup += '<label for="' + checkId + '">' + features[j].trim() + '</label><br/>';
		}
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
			response = JSON.parse(response);
			if (response.markup == undefined || response.markup == "") {
				return;
			}

			var featureList = response.markup.split('\n'),
				table,
				row;

			$('#efl-fl-table').remove();
			$('#efl-fl-hr').remove();

			for (var i = 0; i < featureList.length; i++) {
				if (featureList[i] === undefined) {
					return;
				}

				featureList[i] = featureList[i].split(':');

				if (featureList[i].length != 2) {
					return;
				}

				featureList[i][1] = featureList[i][1].split(',');
			}

			$('#efl-mb-table').after($('<table class="form-table" id="efl-fl-table"></table>')).after($('<hr id="efl-fl-hr"/>'));
			table = $('#efl-fl-table');

			for (var i = 0; i < featureList.length; i++) {
				row = $(table[0].insertRow(-1));
				row.attr('valign', 'top');
				row.append('<th scope="row">' + featureList[i][0].trim() + '</th>');
				row.append('<td>' + createFeatureList(i, featureList[i][1], response.checklist) + '</td>');
			}
		});
	}

	if (window.location.href.search('post.php?') >= 0 || window.location.href.search('post-new.php?') >= 0) {
		$('#efl-list-sel').change(getFeatureList);
		getFeatureList();		
	}
});