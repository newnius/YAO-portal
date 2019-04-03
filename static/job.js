function register_events_job() {
	$('#btn-job-add').click(function (e) {
		$('#modal-job').modal('show');
	});

	$("#form-job-submit").click(function (e) {
		var name = $('#form-job-name').val();
		var image = $('#form-job-image').val();
		var workspace = $('#form-job-workspace').val();
		var cluster = $('#form-job-cluster').val();
		var priority = $('#form-job-priority').val();
		var run_before = $('#form-job-run-before').val();
		if (run_before.length !== 0) {
			run_before = moment(run_before).unix();
		}
		var tasks = [];
		$('#form-job-tasks').find('.row').each(function () {
			var vals = $(this).find('input');
			var task = {};
			task['name'] = vals.eq(0).val();
			task['cmd'] = vals.eq(1).val();
			task['cpu_number'] = vals.eq(2).val();
			task['memory'] = vals.eq(3).val();
			task['gpu_number'] = vals.eq(4).val();
			task['gpu_memory'] = vals.eq(5).val();
			tasks.push(task);
		});

		/* TODO validate form */

		$('#modal-job').modal('hide');

		var ajax = $.ajax({
			url: window.config.BASE_URL + "/service?action=job_submit",
			type: 'POST',
			data: {
				name: name,
				image: image,
				workspace: workspace,
				cluster: cluster,
				priority: priority,
				run_before: run_before,
				tasks: JSON.stringify(tasks)
			}
		});
		ajax.done(function (res) {
			if (res["errno"] !== 0) {
				$("#modal-msg-content").html(res["msg"]);
				$("#modal-msg").modal('show');
			}
			$('#table-job').bootstrapTable("refresh");

		});
		ajax.fail(function (jqXHR, textStatus) {
			$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
			$("#modal-msg").modal('show');
			$('#table-job').bootstrapTable("refresh");
		});
	});

}

function load_jobs(scope) {
	$("#table-job").bootstrapTable({
		url: window.config.BASE_URL + '/service?action=job_list&who=' + scope,
		responseHandler: jobResponseHandler,
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
			field: 'created_by',
			title: 'Created By',
			align: 'center',
			valign: 'middle',
			formatter: UIDFormatter,
			visible: scope === 'all'
		}, {
			field: 'name',
			title: 'Name',
			align: 'center',
			valign: 'middle',
			escape: true
		}, {
			field: 'image',
			title: 'Docker Image',
			align: 'center',
			valign: 'middle',
			visible: false,
			escape: true
		}, {
			field: 'workspace',
			title: 'Workspace',
			align: 'center',
			valign: 'middle',
			visible: false,
			formatter: workspaceFormatter
		}, {
			field: 'virtual_cluster',
			title: 'Virtual Cluster',
			align: 'center',
			valign: 'middle',
			formatter: clusterFormatter
		}, {
			field: 'priority',
			title: 'Priority',
			align: 'center',
			valign: 'middle',
			formatter: priorityFormatter
		}, {
			field: 'run_before',
			title: 'Run Before',
			align: 'center',
			valign: 'middle',
			visible: false,
			formatter: timeFormatter
		}, {
			field: 'created_at',
			title: 'Created At',
			align: 'center',
			valign: 'middle',
			formatter: timeFormatter
		}, {
			field: 'status',
			title: 'Status',
			align: 'center',
			valign: 'middle',
			formatter: statusFormatter,
			visible: true
		}, {
			field: 'operate',
			title: 'Operate',
			align: 'center',
			events: jobOperateEvents,
			formatter: jobOperateFormatter
		}]
	});
}

var UIDFormatter = function (UID) {
	return UID;
};

var workspaceFormatter = function (workspace) {
	return workspace;
};

var clusterFormatter = function (cluster) {
	return cluster;
};

var priorityFormatter = function (status) {
	status = parseInt(status);
	switch (status) {
		case 1:
			return '<span class="text-normal">Low</span>';
		case 25:
			return '<span class="text-info">Medium</span>';
		case 50:
			return '<span class="text-success">High</span>';
		case 99:
			return '<span class="text-danger">Urgent</span>';
	}
	return 'Unknown (' + status + ')';
};

var statusFormatter = function (status) {
	status = parseInt(status);
	switch (status) {
		case 0:
			return '<span class="text-normal">Created</span>';
		case 1:
			return '<span class="text-normal">Starting</span>';
		case 2:
			return '<span class="text-info">Running</span>';
		case 3:
			return '<span class="text-danger">Stopped</span>';
		case 4:
			return '<span class="text-success">Finished</span>';
	}
	return 'Unknown(' + status + ')';
};

function jobResponseHandler(res) {
	if (res['errno'] === 0) {
		var tmp = {};
		tmp["total"] = res["count"];
		tmp["rows"] = res["jobs"];
		return tmp;
	}
	$("#modal-msg-content").html(res["msg"]);
	$("#modal-msg").modal('show');
	return [];
}

function jobOperateFormatter(value, row, index) {
	var div = '<div class="btn-group" role="group" aria-label="...">';
	if (page_type === 'jobs')
		div += '<button class="btn btn-default config"><i class="glyphicon glyphicon-cog"></i>&nbsp;</button>';
	if (page_type === 'jobs')
		div += '<button class="btn btn-default stats"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;</button>';
	if (page_type === 'jobs' && (parseInt(row.status) === 0 || parseInt(row.status) === 1))
		div += '<button class="btn btn-default stop"><i class="glyphicon glyphicon-remove"></i>&nbsp;</button>';
	div += '</div>';
	return div;
}

window.jobOperateEvents = {
	'click .config': function (e, value, row, index) {
		row.tasks = JSON.parse(row.tasks);
		var formattedData = JSON.stringify(row, null, '\t');
		$('#modal-job-description-content').text(formattedData);
		$('#modal-job-description').modal('show');
	},
	'click .stats': function (e, value, row, index) {
		window.open("?job_status&name=" + row.name);
	},
	'click .stop': function (e, value, row, index) {
		if (!confirm('Are you sure to stop this job?')) {
			return;
		}
		var ajax = $.ajax({
			url: window.config.BASE_URL + "/service?action=job_stop",
			type: 'POST',
			data: {id: row.id}
		});
		ajax.done(function (res) {
			if (res["errno"] !== 0) {
				$("#modal-msg-content").html(res["msg"]);
				$("#modal-msg").modal('show');
			}
			$('#table-link').bootstrapTable("refresh");
		});
		ajax.fail(function (jqXHR, textStatus) {
			$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
			$("#modal-msg").modal('show');
			$('#table-job').bootstrapTable("refresh");
		});
	}
};

function load_job_status(name) {
	$("#table-task").bootstrapTable({
		url: window.config.BASE_URL + '/service?action=job_status&name=' + name,
		responseHandler: jobStatusResponseHandler,
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
			valign: 'middle'
		}, {
			field: 'image',
			title: 'Image',
			align: 'center',
			valign: 'middle',
			visible: false
		}, {
			field: 'image_digest',
			title: 'Image Version',
			align: 'center',
			valign: 'middle',
			visible: false
		}, {
			field: 'hostname',
			title: 'Hostname',
			align: 'center',
			valign: 'middle'
		}, {
			field: 'command',
			title: 'Command',
			align: 'center',
			valign: 'middle'
		}, {
			field: 'created_at',
			title: 'Created At',
			align: 'center',
			valign: 'middle'
		}, {
			field: 'finished_at',
			title: 'Finished At',
			align: 'center',
			valign: 'middle',
			visible: false
		}, {
			field: 'status',
			title: 'Status',
			align: 'center',
			valign: 'middle'
		}, {
			field: 'operate',
			title: 'Operate',
			align: 'center',
			events: jobStatusOperateEvents,
			formatter: jobStatusOperateFormatter
		}]
	});
}

function jobStatusResponseHandler(res) {
	if (res['errno'] === 0) {
		var tmp = {};
		tmp["total"] = res["count"];
		tmp["rows"] = res["tasks"];
		return tmp;
	}
	$("#modal-msg-content").html(res["msg"]);
	$("#modal-msg").modal('show');
	return [];
}

function jobStatusOperateFormatter(value, row, index) {
	var div = '<div class="btn-group" role="group" aria-label="...">';
	div += '<button class="btn btn-default logs"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;</button>';
	div += '</div>';
	return div;
}

window.jobStatusOperateEvents = {
	'click .logs': function (e, value, row, index) {
		var job = getParameterByName('name');
		var task = row.id;

		var ajax = $.ajax({
			url: window.config.BASE_URL + "/service?action=task_logs",
			type: 'GET',
			data: {
				job: job,
				task: task
			}
		});
		ajax.done(function (res) {
			if (res["errno"] !== 0) {
				$("#modal-msg-content").html(res["msg"]);
				$("#modal-msg").modal('show');
			}
			$('#modal-task-logs-content').text(res['logs']);
			$('#modal-task-logs').modal('show');
		});
		ajax.fail(function (jqXHR, textStatus) {
			$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
			$("#modal-msg").modal('show');
			$('#table-job').bootstrapTable("refresh");
		});
	}
};