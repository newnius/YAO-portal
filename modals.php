<!-- msg modal -->
<div class="modal fade" id="modal-msg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content panel-warning">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 id="modal-msg-title" class="modal-title">Notice</h4>
			</div>
			<div class="modal-body">
				<h4 id="modal-msg-content" class="text-msg text-center">Something is wrong!</h4>
			</div>
		</div>
	</div>
</div>

<!-- job description modal -->
<div class="modal fade" id="modal-job-description" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content panel-info">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Describe This Job</h4>
			</div>
			<div class="modal-body">
				<pre id="modal-job-description-content"></pre>
			</div>
		</div>
	</div>
</div>

<!-- node GPU detail modal -->
<div class="modal fade" id="modal-resource-gpu-detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content panel-info">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">GPUs on this node</h4>
			</div>
			<div class="modal-body">
				<pre id="modal-resource-gpu-detail-content"></pre>
			</div>
		</div>
	</div>
</div>

<!-- task logs modal -->
<div class="modal fade" id="modal-task-logs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content panel-info">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">Task Outputs</h4>
			</div>
			<div class="modal-body">
				<pre id="modal-task-logs-content"></pre>
			</div>
		</div>
	</div>
</div>

<!-- job modal -->
<div class="modal fade" id="modal-job" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content panel-info">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 id="modal-job-title" class="modal-title">Submit New Job</h4>
			</div>
			<div class="modal-body">
				<form class="form" action="javascript:void(0)">
					<label>Job Name</label>
					<div class="form-group form-group-lg">
						<label for="form-job-name" class="sr-only">Job Name</label>
						<input type="text" id="form-job-name" class="form-control" maxlength="64"
						       placeholder="A readable job name" required/>
					</div>
					<label>Workspace</label>
					<div class="form-group form-group-lg">
						<label for="form-job-workspace" class="sr-only">Workspace</label>
						<select id="form-job-workspace" class="form-control">
							<option value="">None</option>
						</select>
					</div>
					<label>Virtual Cluster</label>
					<div class="form-group form-group-lg">
						<label for="form-job-cluster" class="sr-only">Virtual Cluster</label>
						<select id="form-job-cluster" class="form-control">
							<option value="1">default</option>
						</select>
					</div>
					<label>Priority</label>
					<div class="form-group form-group-lg">
						<label for="form-job-priority" class="sr-only">Job Priority</label>
						<select id="form-job-priority" class="form-control">
							<option value="99">Urgent</option>
							<option value="50">High</option>
							<option value="25" selected>Medium</option>
							<option value="1">Low</option>
						</select>
					</div>
					<label class="hidden">Run Before</label>
					<div class="form-group form-group-lg hidden">
						<div class='input-group date date-picker'>
							<label for="form-job-run-before" class="sr-only">Run Before</label>
							<input type='text' class="form-control" placeholder="Run this job before"
							       id="form-job-run-before"
							       autocomplete="off"/>
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</div>
						</div>
					</div>
					<label>Environment</label>
					<div id="form-job-tasks">
						<div class="row">
							<div class="col-md-6">
								<label>Docker Image</label>
								<select class="form-control form-control task-image" required>
									<option value="quickdeploy/yao-tensorflow:1.12" selected>
										quickdeploy/yao-tensorflow:1.12
									</option>
									<option value="nvidia/cuda:9.0-base">nvidia/cuda:9.0-base</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>CMD</label>
								<div class="form-group">
									<input type="text" class="form-control task-cmd" maxlength="255"
									       placeholder="Command to bring up task"/>
								</div>
							</div>

							<div class="col-md-4 hidden">
								<label>Name</label>
								<div class="form-group">
									<input type="text" class="form-control task-name" maxlength="32"
									       placeholder="Task Name & Node Name" value="node1" required/>
								</div>
							</div>
							<div class="col-md-2">
								<label>CPU Number</label>
								<div class="form-group">
									<input type="number" class="form-control task-cpu" step="1" min="1" value="1"
									       placeholder="number of CPU required" required/>
								</div>
							</div>
							<div class="col-md-2">
								<label>Memory</label>
								<div class="form-group">
									<input type="number" class="form-control task-mem" step="1024" min="1024"
									       value="4096" placeholder="MB" required/>
								</div>
							</div>
							<div class="col-md-2">
								<label>GPU Number (Available: 4)</label>
								<div class="form-group">
									<input type="number" class="form-control task-gpu-num" step="1" min="1" value="1"
									       placeholder="number of GPU cards required" required/>
								</div>
							</div>
							<div class="col-md-2">
								<label>GPU Memory(Left:N GB)</label>
								<div class="form-group">
									<input type="number" class="form-control task-gpu-mem" step="1024" min="1024"
									       value="4096" placeholder="MB" required/>
								</div>
							</div>
						</div>
					</div>
					<div>
						<button id="form-job-submit" type="submit" class="btn btn-primary btn-lg">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- agent modal -->
<div class="modal fade" id="modal-agent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content panel-info">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 id="modal-agent-title" class="modal-title">Add New Agent</h4>
			</div>
			<div class="modal-body">
				<form class="form" action="javascript:void(0)">
					<label>IP</label>
					<div class="form-group form-group-lg">
						<label for="form-agent-ip" class="sr-only">IP</label>
						<input type="text" id="form-agent-ip" class="form-control" maxlength="64"
						       placeholder="10.0.0.1" required/>
					</div>
					<label>Alias</label>
					<div class="form-group form-group-lg">
						<label for="form-agent-alias" class="sr-only">Alias</label>
						<input type="text" id="form-agent-alias" class="form-control" maxlength="32"
						       placeholder="bj.node1"/>
					</div>
					<label>Cluster</label>
					<div class="form-group form-group-lg">
						<label for="form-agent-cluster" class="sr-only">Cluster</label>
						<select id="form-agent-cluster" class="form-control">
							<option value="0">default</option>
						</select>
					</div>
					<label>Token</label>
					<div class="form-group form-group-lg">
						<label for="form-agent-token" class="sr-only">Token</label>
						<input type="text" id="form-agent-token" class="form-control" placeholder="******" readonly/>
					</div>
					<div>
						<input type="hidden" id="form-agent-submit-type"/>
						<input type="hidden" id="form-agent-id"/>
						<button id="form-agent-submit" type="submit" class="btn btn-primary btn-lg">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- workspace modal -->
<div class="modal fade" id="modal-workspace" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content panel-info">
			<div class="modal-header panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 id="modal-workspace-title" class="modal-title">Add New Workspace</h4>
			</div>
			<div class="modal-body">
				<form class="form" action="javascript:void(0)">
					<label>Name</label>
					<div class="form-group form-group-lg">
						<label for="form-agent-ip" class="sr-only">IP</label>
						<input type="text" id="form-workspace-name" class="form-control" maxlength="64"
						       placeholder="" required/>
					</div>
					<label>Type</label>
					<div class="form-group form-group-lg">
						<label for="form-workspace-type" class="sr-only">Type</label>
						<select id="form-workspace-type" class="form-control">
							<option value="git">git</option>
						</select>
					</div>
					<label>Repo</label>
					<div class="form-group form-group-lg">
						<label for="form-workspace-git-repo" class="sr-only">Git Repo</label>
						<input type="text" id="form-workspace-git-repo" class="form-control"
						       placeholder="http://192.168.100.100:3000/newnius/tf.git"/>
					</div>
					<div>
						<input type="hidden" id="form-workspace-submit-type"/>
						<input type="hidden" id="form-workspace-id"/>
						<input type="hidden" id="form-workspace-content"/>
						<button id="form-workspace-submit" type="submit" class="btn btn-primary btn-lg">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>