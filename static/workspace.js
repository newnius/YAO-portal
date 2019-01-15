function register_events_workspace() {
	$('#btn-workspace-add').click(function (e) {
		$('#form-workspace-submit-type').val('add');
		$('#modal-workspace').modal('show');
	});

	$("#form-workspace-submit").click(function (e) {
		var id = $('#form-workspace-id').val();
		var name = $('#form-workspace-name').val();
		var content = $('#form-workspace-content').val();
		var virtual_cluster = $('#form-workspace-virtual-cluster').val();
		var permission = $('#form-workspace-permission').val();

		/* TODO validate form */

		$('#modal-workspace').modal('hide');
		var action = 'workspace_add';
		if ($('#form-workspace-submit-type').val() !== 'add')
			action = 'workspace_update';

		var ajax = $.ajax({
			url: window.config.BASE_URL + "/service?action=" + action,
			type: 'POST',
			data: {
				id: id,
				name: name,
				content: "[]",
				virtual_cluster: virtual_cluster,
				permission: permission
			}
		});
		ajax.done(function (res) {
			if (res["errno"] !== 0) {
				$("#modal-msg-content").html(res["msg"]);
				$("#modal-msg").modal('show');
			}
			$('#table-workspace').bootstrapTable("refresh");

		});
		ajax.fail(function (jqXHR, textStatus) {
			$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
			$("#modal-msg").modal('show');
			$('#table-workspace').bootstrapTable("refresh");
		});
	});

}

function load_workspaces(cluster) {
	$("#table-workspace").bootstrapTable({
		url: window.config.BASE_URL + '/service?action=workspace_list&who=' + cluster,
		responseHandler: workspaceResponseHandler,
		sidePagination: 'server',
		cache: true,
		striped: true,
		pagination: true,
		pageSize: 10,
		pageList: [10, 25, 50, 100, 200],
		search: false,
		showColumns: true,
		showRefresh: true,
		showToggle: false,
		showPaginationSwitch: true,
		minimumCountColumns: 2,
		clickToSelect: false,
		sortName: 'nobody',
		sortOrder: 'desc',
		smartDisplay: true,
		mobileResponsive: true,
		showExport: true,
		columns: [{
			field: 'id',
			title: 'ID',
			align: 'center',
			valign: 'middle',
			visible: false
		}, {
			field: 'name',
			title: 'Name',
			align: 'center',
			valign: 'middle',
			escape: true
		}, {
			field: 'virtual_cluster',
			title: 'Virtual Cluster',
			align: 'center',
			valign: 'middle'
		}, {
			field: 'permission',
			title: 'Permission',
			align: 'center',
			valign: 'middle'
		}, {
			field: 'operate',
			title: 'Operate',
			align: 'center',
			events: workspaceOperateEvents,
			formatter: workspaceOperateFormatter
		}]
	});
}

function workspaceResponseHandler(res) {
	if (res['errno'] === 0) {
		var tmp = {};
		tmp["total"] = res["count"];
		tmp["rows"] = res["workspaces"];
		return tmp;
	}
	$("#modal-msg-content").html(res["msg"]);
	$("#modal-msg").modal('show');
	return [];
}

function workspaceOperateFormatter(value, row, index) {
	var div = '<div class="btn-group" role="group" aria-label="...">';
	div += '<button class="btn btn-default view"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;</button>';
	div += '<button class="btn btn-default edit"><i class="glyphicon glyphicon-edit"></i>&nbsp;</button>';
	div += '<button class="btn btn-default remove"><i class="glyphicon glyphicon-remove"></i>&nbsp;</button>';
	div += '</div>';
	return div;
}

window.workspaceOperateEvents = {
	'click .view': function (e, value, row, index) {
		$('#form-workspace-id').val(row.id);
		$('#form-workspace-submit-type').val('view');
		$('#modal-workspace').modal('show');
	},
	'click .edit': function (e, value, row, index) {
		$('#form-workspace-id').val(row.id);
		$('#form-workspace-submit-type').val('view');
		$('#modal-workspace').modal('show');
	},
	'click .remove': function (e, value, row, index) {
		if (!confirm('Are you sure to remove this workspace?')) {
			return;
		}
		var ajax = $.ajax({
			url: window.config.BASE_URL + "/service?action=workspace_remove",
			type: 'POST',
			data: {id: row.id}
		});
		ajax.done(function (res) {
			if (res["errno"] !== 0) {
				$("#modal-msg-content").html(res["msg"]);
				$("#modal-msg").modal('show');
			}
			$('#table-workspace').bootstrapTable("refresh");
		});
		ajax.fail(function (jqXHR, textStatus) {
			$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
			$("#modal-msg").modal('show');
			$('#table-workspace').bootstrapTable("refresh");
		});
	}
};