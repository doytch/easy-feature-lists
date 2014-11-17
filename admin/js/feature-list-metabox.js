jQuery(document).ready( function($) {
var EFL_GROUPS = [],
	EFL_MARKUP = {
		groupRow:
			"<tr class='efl-group-row efl-group-row-top' valign='top'>\
				<th scope='row'><label>&nbsp;Group Name</label></th>\
				<td><input class='efl-group-name-text' id='efl-group-name-text-__GROUP_ID__' name='efl-group-name[__GROUP_ID__]' type='text'/></td>\
			</tr>\
			<tr class='efl-group-row' valign='top'>\
				<th scope='row'>&nbsp;<button efl-group='__GROUP_ID__' class='efl-add-feature-btn'>Add Feature</button></th>\
				<td></td>\
			</tr>\
			<tr class='efl-group-row efl-group-row-bottom' valign='top'>\
				<th scope='row'>\
					<label>&nbsp;Features</label>\
				</th>\
				<td>\
					<table id='efl-feature-group-table-__GROUP_ID__' class='efl-feature-group-table'>\
						<tr>\
							<th class='efl-th efl-th-remove'></th>\
							<th class='efl-th efl-th-name'>Feature Label</th>\
							<th class='efl-th efl-th-type'>Feature Type</th></tr>\
					</table>\
				</td>\
			</tr>",
		groupTableRow: 
			"<tr>\
				<td><button class='efl-remove-feature-btn'>X</button></td>\
				<td><input class='efl-feature-name-text' name='efl-feature-name[__GROUP_ID__][]' type='text'/></td>\
				<td><select class='efl-feature-type-sel' name='efl-feature-type[__GROUP_ID__][]'><option>Checkbox</option><option>Text</option></td>\
			</tr>"
	};

function FeatureGroupTable () {
	this.id = EFL_GROUPS.length;
	this.featureTableSelector = this.createFeatureGroup();
	this.nameTextSelector = $('#efl-group-name-text-' + this.id);

	this.registerEvents();
	EFL_GROUPS.push(this);
	return this;
}

FeatureGroupTable.prototype.createFeatureGroup = function() {
	var markup = $(EFL_MARKUP.groupRow.replace(/__GROUP_ID__/g, this.id));
	return markup.appendTo($('#efl-list-table')).find('.efl-feature-group-table');
}

FeatureGroupTable.prototype.registerEvents = function() {
	$('.efl-add-feature-btn').off('click').click(function(event) {
		event.preventDefault();

		var table = FeatureGroupTable.getTableById($(this).attr('efl-group'));
		new FeatureGroupRow(table);
	});
}

FeatureGroupTable.getTableById = function (id) {
	return EFL_GROUPS[parseInt(id)];
}

function FeatureGroupRow (table) {
	this.selector = $(EFL_MARKUP.groupTableRow.replace(/__GROUP_ID__/g, table.id)).appendTo(table.featureTableSelector.children('tbody'));
	this.table = table;

	this.registerEvents();
	return this;
}

FeatureGroupRow.prototype.registerEvents = function() {
	this.table.featureTableSelector.find('.efl-remove-feature-btn').off('click').click(function(event) {
		event.preventDefault();

		$(this).parent().parent().remove();
	});
}

registerPageEvents = function() {
	$('#efl-add-feature-group-btn').click(function(event) {
		event.preventDefault();

		var table = new FeatureGroupTable();
		new FeatureGroupRow(table);

	});	
}

reloadData = function() {
	if (EFL_LIST_JSON === "") return;

	var list = JSON.parse(EFL_LIST_JSON),
		group,
		table,
		feature,
		row;

	for (var i = 0; i < list.length; i++) {
		group = list[i];
		table = new FeatureGroupTable();
		table.nameTextSelector.val(group.name);

		for (var j = 0; j < group.features.length; j++) {
			feature = group.features[j];
			row = new FeatureGroupRow(table);
			row.selector.find('.efl-feature-name-text').val(feature.name);
			row.selector.find('.efl-feature-type-sel').val(feature.type);
		}
	}
}

initPage = function() {
	registerPageEvents();
	reloadData();
}


initPage()

});
