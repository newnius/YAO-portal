function register_events_summary() {

}

function summary_render() {
	var ctx_cpu = document.getElementById('summary-chart-cpu').getContext('2d');
	var ctx_mem = document.getElementById('summary-chart-mem').getContext('2d');
	var ctx_jobs = document.getElementById('summary-chart-jobs').getContext('2d');
	var ctx_gpu = document.getElementById('summary-chart-gpu').getContext('2d');

	var ajax = $.ajax({
		url: window.config.BASE_URL + "/service?action=summary_get",
		type: 'GET',
		data: {}
	});
	ajax.done(function (res) {
		if (res["errno"] !== 0) {
			$("#modal-msg-content").html(res["msg"]);
			$("#modal-msg").modal('show');
		}


		ctx_cpu.canvas.height = 200;
		new Chart(ctx_cpu, {
			"type": "line",
			"data": {
				"labels": ["January", "February", "March", "April", "May", "June", "July"],
				"datasets": [{
					"label": "My First Data set",
					"data": [2, 0.5, 1.5, 0.81, 1.56, 1.55, 1.40],
					"fill": true,
					"borderColor": "rgb(75, 192, 192)",
					"lineTension": 0.1
				}]
			},
			"options": {
				legend: {
					display: false
				},
				maintainAspectRatio:false

			}
		});


		var data = {
			datasets: [{
				data: Object.values(res['jobs']),
				backgroundColor: ["rgb(54, 162, 235)", "rgb(255, 99, 132)", "rgb(255, 205, 86)"]
			}],

			// These labels appear in the legend and in the tooltips when hovering different arcs
			labels: Object.keys(res['jobs'])
		};
		var myPieChart = new Chart(ctx_jobs, {
			type: 'pie',
			data: data,
			options: {
				legend: {
					display: false
				}
			}
		});

		var data2 = {
			datasets: [{
				data: Object.values(res['gpu']),
				backgroundColor: ["rgb(54, 162, 235)", "rgb(255, 99, 132)"]
			}],

			// These labels appear in the legend and in the tooltips when hovering different arcs
			labels: Object.keys(res['gpu'])
		};
		var myPieChart2 = new Chart(ctx_gpu, {
			type: 'pie',
			data: data2,
			options: {
				legend: {
					display: false
				}
			}
		});
	});
	ajax.fail(function (jqXHR, textStatus) {
		$("#modal-msg-content").html("Request failed : " + jqXHR.statusText);
		$("#modal-msg").modal('show');
	});
}