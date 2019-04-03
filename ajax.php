<?php

require_once('util4p/util.php');
require_once('util4p/CRObject.class.php');

require_once('Code.class.php');
require_once('Securer.class.php');

require_once('user.logic.php');
require_once('job.logic.php');
require_once('agent.logic.php');
require_once('workspace.logic.php');

require_once('config.inc.php');
require_once('init.inc.php');


function csrf_check($action)
{
	/* check referer, just in case I forget to add the method to $post_methods */
	$referer = cr_get_SERVER('HTTP_REFERER', '');
	$url = parse_url($referer);
	$host = isset($url['host']) ? $url['host'] : '';
	$host .= isset($url['port']) && $url['port'] !== 80 ? ':' . $url['port'] : '';
	if ($host !== cr_get_SERVER('HTTP_HOST')) {
		return false;
	}
	$post_methods = array(
		'signout',
		'oauth_get_url'
	);
	if (in_array($action, $post_methods)) {
		return Securer::validate_csrf_token();
	}
	return true;
}

function print_response($res)
{
	if (!isset($res['msg']))
		$res['msg'] = Code::getErrorMsg($res['errno']);
	$json = json_encode($res);
	header('Content-type: application/json');
	echo $json;
}


$res = array('errno' => Code::UNKNOWN_REQUEST);

$action = cr_get_GET('action');

if (!csrf_check($action)) {
	$res['errno'] = 99;
	$res['msg'] = 'invalid csrf_token';
	print_response($res);
	exit(0);
}

switch ($action) {
	case 'job_list':
		$rule = new CRObject();
		$rule->set('who', cr_get_GET('who', 'self'));
		$rule->set('offset', cr_get_GET('offset'));
		$rule->set('limit', cr_get_GET('limit'));
		$rule->set('order', 'latest');
		$res = job_list($rule);
		break;

	case 'job_submit':
		$job = new CRObject();
		$job->set('name', cr_get_POST('name', '') . '-' . time());
		$job->set('virtual_cluster', cr_get_POST('cluster'));
		$job->set('workspace', cr_get_POST('workspace'));
		$job->set('priority', cr_get_POST('priority'));
		$job->set('image', cr_get_POST('image'));
		$job->set('run_before', cr_get_POST('run_before'));
		$job->set('tasks', cr_get_POST('tasks'));
		$res = job_submit($job);
		break;

	case 'job_stop':
		$job = new CRObject();
		$job->set('id', cr_get_POST('id'));
		$res = job_stop($job);
		break;

	case 'job_describe':
		$job = new CRObject();
		$job->set('id', cr_get_POST('id'));
		$res = job_describe($job);
		break;

	case 'job_status':
		$job = new CRObject();
		$job->set('name', cr_get_GET('name'));
		$res = job_status($job);
		break;

	case 'summary_get':
		$res = summary_get();
		break;

	case 'task_logs':
		$task = new CRObject();
		$task->set('job', cr_get_GET('job'));
		$task->set('task', cr_get_GET('task'));
		$res = task_logs($task);
		break;

	case 'agent_list':
		$rule = new CRObject();
		$rule->set('offset', cr_get_GET('offset'));
		$rule->set('limit', cr_get_GET('limit'));
		$res = agent_list($rule);
		break;

	case 'agent_add':
		$agent = new CRObject();
		$agent->set('ip', cr_get_POST('ip'));
		$agent->set('alias', cr_get_POST('alias'));
		$agent->set('cluster', cr_get_POST('cluster'));
		$res = agent_add($agent);
		break;

	case 'agent_remove':
		$job = new CRObject();
		$job->set('id', cr_get_POST('id'));
		$res = agent_remove($job);
		break;

	case 'workspace_list':
		$rule = new CRObject();
		$rule->set('offset', cr_get_GET('offset'));
		$rule->set('limit', cr_get_GET('limit'));
		$res = workspace_list($rule);
		break;

	case 'workspace_add':
		$workspace = new CRObject();
		$workspace->set('name', cr_get_POST('name'));
		$workspace->set('content', cr_get_POST('content'));
		$workspace->set('virtual_cluster', cr_get_POST('virtual_cluster'));
		$workspace->set('permission', cr_get_POST('permission'));
		$res = workspace_add($workspace);
		break;

	case 'workspace_update':
		$workspace = new CRObject();
		$workspace->set('id', cr_get_POST('id'));
		$workspace->set('name', cr_get_POST('name'));
		$workspace->set('content', cr_get_POST('content'));
		$workspace->set('virtual_cluster', cr_get_POST('virtual_cluster'));
		$workspace->set('permission', cr_get_POST('permission'));
		$res = workspace_update($workspace);
		break;

	case 'workspace_remove':
		$workspace = new CRObject();
		$workspace->set('id', cr_get_POST('id'));
		$res = workspace_remove($workspace);
		break;

	case 'user_signout':
		$res = user_signout();
		break;

	case 'log_gets':
		$rule = new CRObject();
		$rule->set('who', cr_get_GET('who', 'self'));
		$rule->set('offset', cr_get_GET('offset'));
		$rule->set('limit', cr_get_GET('limit'));
		$rule->set('order', 'latest');
		$res = log_gets($rule);
		break;

	case 'oauth_get_url':
		$res = oauth_get_url();
		break;

	default:
		break;
}

print_response($res);
