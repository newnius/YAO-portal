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
					<label>Docker Image</label>
					<div class="form-group form-group-lg">
						<label for="form-job-image" class="sr-only">Docker Image</label>
						<input type="text" id="form-job-image" class="form-control" maxlength="256"
						       placeholder="eg. yao/tensorflow:1.12" required/>
					</div>
					<label>Workspace</label>
					<div class="form-group form-group-lg">
						<label for="form-job-workspace" class="sr-only">Workspace</label>
						<select id="form-job-workspace" class="form-control">
							<option value="1">Workspace 1</option>
						</select>
					</div>
					<label>Virtual Cluster</label>
					<div class="form-group form-group-lg">
						<label for="form-job-cluster" class="sr-only">Virtual Cluster</label>
						<select id="form-job-cluster" class="form-control">
							<option value="1">Cluster 1</option>
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
					<label>Run Before</label>
					<div class="form-group form-group-lg">
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
					<label>Tasks</label>
					<div class="row" id="form-job-tasks">
						<div class="col-md-2">
							<label>Name</label>
							<div class="form-group">
								<input type="text" class="form-control" maxlength="32"
								       placeholder="Task Name & Node Name" required/>
							</div>
						</div>
						<div class="col-md-2">
							<label>CMD</label>
							<div class="form-group">
								<input type="text" class="form-control" maxlength="255"
								       placeholder="Command to bring up task" required/>
							</div>
						</div>
						<div class="col-md-2">
							<label>CPU Number</label>
							<div class="form-group">
								<input type="number" class="form-control" step="1" min="1"
								       placeholder="number of CPU required" required/>
							</div>
						</div>
						<div class="col-md-2">
							<label>Memory</label>
							<div class="form-group">
								<input type="number" class="form-control" step="512" min="512"
								       placeholder="MB" required/>
							</div>
						</div>
						<div class="col-md-2">
							<label>GPU Number</label>
							<div class="form-group">
								<input type="number" class="form-control" step="1" min="1"
								       placeholder="number of GPU cards required" required/>
							</div>
						</div>
						<div class="col-md-2">
							<label>GPU Memory</label>
							<div class="form-group">
								<input type="number" class="form-control" step="512" min="512"
								       placeholder="MB" required/>
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
					<label>Virtual Cluster</label>
					<div class="form-group form-group-lg">
						<label for="form-workspace-virtual-cluster" class="sr-only">Virtual Cluster</label>
						<select id="form-workspace-virtual-cluster" class="form-control">
							<option value="0">default</option>
						</select>
					</div>
					<label>Permission</label>
					<div class="form-group form-group-lg">
						<label for="form-workspace-permission" class="sr-only">Token</label>
						<input type="number" id="form-workspace-permission" class="form-control" placeholder=""/>
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