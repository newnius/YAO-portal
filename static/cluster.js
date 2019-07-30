function register_events_cluster() {
	$('#btn-cluster-add').click(function (e) {
		$('#form-cluster-submit-type').val('add');
		$('#modal-cluster').modal('show');
	});

	$("#form-cluster-submit").click(function (e) {
		var name = $('#form-cluster-name').val();
		var weight = $('#form-cluster-weight').val();
		var reserved = $('#form-cluster-reserved').prop("checked");
		var quota_gpu = $('#form-cluster-quota-gpu-number').val();
		var quota_gpu_mem = $('#form-cluster-quota-gpu-memory').val();
		var quota_cpu = $('#form-cluster-quota-cpu').val();
		var quota_mem = $('#form-cluster-quota-mem').val();

		/* TODO: validate form */

		$('#modal-cluster').modal('hide');
		if ($('#form-cluster-submit-type').val() !== 'add')
			return;

		var ajax = $.ajax({
			url: "service?action=cluster_add",
			type: 'POST',
			data: {
				name: name,
				weight: weight,
				reserved: reserved,
				quota_gpu: quota_gpu,
				quota_gpu_mem: quota_gpu_mem,
				quota_cpu: quota_cpu,
				quota_mem: quota_mem
			}
		});
		ajax.done(function (res) {
			if (res["errno"] !== 0) {
				$("#modal-msg-content").html(res["msg"]);
				$("#modal-msg").modal('show');
			}
			$('#table-cluster').bootstrapTable("refresh");
		});
		ajax.fail(function (jqXHR, textStatus) {
			$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
			$("#modal-msg").modal('show');
			$('#table-cluster').bootstrapTable("refresh");
		});
	});

}

function load_clusters() {
	$("#table-cluster").bootstrapTable({
		url: 'service?action=cluster_list',
		responseHandler: clusterResponseHandler,
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
			field: 'name',
			title: 'Name',
			align: 'center',
			valign: 'middle'
		}, {
			field: 'operate',
			title: 'Operate',
			align: 'center',
			events: clusterOperateEvents,
			formatter: clusterOperateFormatter
		}]
	});
}

function clusterResponseHandler(res) {
	if (res['errno'] === 0) {
		var tmp = {};
		tmp["total"] = res["count"];
		tmp["rows"] = res["clusters"];
		return tmp;
	}
	$("#modal-msg-content").html(res["msg"]);
	$("#modal-msg").modal('show');
	return [];
}

function clusterOperateFormatter(value, row, index) {
	var div = '<div class="btn-group" role="group" aria-label="...">';
	div += '<button class="btn btn-default view"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;</button>';
	div += '<button class="btn btn-default remove"><i class="glyphicon glyphicon-remove"></i>&nbsp;</button>';
	div += '</div>';
	return div;
}

window.clusterOperateEvents = {
	'click .view': function (e, value, row, index) {
		$('#form-agent-name').val(row.name);
		$('#modal-cluster').modal('show');
	},
	'click .remove': function (e, value, row, index) {
		if (!confirm('Are you sure to remove this virtual cluster?')) {
			return;
		}
		var ajax = $.ajax({
			url: "service?action=cluster_remove",
			type: 'POST',
			data: {name: row.name}
		});
		ajax.done(function (res) {
			if (res["errno"] !== 0) {
				$("#modal-msg-content").html(res["msg"]);
				$("#modal-msg").modal('show');
			}
			$('#table-cluster').bootstrapTable("refresh");
		});
		ajax.fail(function (jqXHR, textStatus) {
			$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
			$("#modal-msg").modal('show');
			$('#table-cluster').bootstrapTable("refresh");
		});
	}
};