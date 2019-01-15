<?php

require_once('predis/autoload.php');

require_once('util4p/util.php');
require_once('util4p/CRObject.class.php');
require_once('util4p/AccessController.class.php');
require_once('util4p/CRLogger.class.php');

require_once('Code.class.php');
require_once('JobManager.class.php');
require_once('Spider.class.php');

require_once('config.inc.php');
require_once('init.inc.php');

function job_submit(CRObject $job)
{
	if (!AccessController::hasAccess(Session::get('role', 'visitor'), 'job.submit')) {
		$res['errno'] = Code::NO_PRIVILEGE;
		return $res;
	}
	$job->set('created_by', Session::get('uid'));
	$res['errno'] = JobManager::add($job) ? Code::SUCCESS : Code::UNKNOWN_ERROR;
	$log = new CRObject();
	$log->set('scope', Session::get('uid'));
	$log->set('tag', 'job.submit');
	$content = array('job' => $job, 'response' => $res['errno']);
	$log->set('content', json_encode($content));
	CRLogger::log($log);
	/* TODO notify scheduler */
	return $res;
}

function job_stop(CRObject $job)
{
	if (!AccessController::hasAccess(Session::get('role', 'visitor'), 'job.stop')) {
		$res['errno'] = Code::NO_PRIVILEGE;
		return $res;
	}
	$origin = JobManager::get($job);
	if ($origin === null) {
		$res['errno'] = Code::RECORD_NOT_EXIST;
	} else if ($origin['created_by'] !== Session::get('uid') && !AccessController::hasAccess(Session::get('role', 'visitor'), 'job.stop_others')) {
		$res['errno'] = Code::NO_PRIVILEGE;
	} else if ($origin['status'] !== '0' && $origin['status'] !== '1') {
		$res['errno'] = Code::RECORD_REMOVED;
	} else {
		$origin['status'] = 4;
		$res['errno'] = JobManager::update(new CRObject($origin)) ? Code::SUCCESS : Code::UNKNOWN_ERROR;
	}
	$log = new CRObject();
	$log->set('scope', Session::get('uid'));
	$log->set('tag', 'job.stop');
	$content = array('id' => $job->getInt('id'), 'response' => $res['errno']);
	$log->set('content', json_encode($content));
	CRLogger::log($log);
	return $res;
}

function job_list(CRObject $rule)
{
	if (!AccessController::hasAccess(Session::get('role', 'visitor'), 'job.list')) {
		$res['errno'] = Code::NO_PRIVILEGE;
		return $res;
	}
	if ($rule->get('who') !== 'all') {
		$rule->set('who', 'self');
		$rule->set('created_by', Session::get('uid'));
	}
	if ($rule->get('who') === 'all' && !AccessController::hasAccess(Session::get('role', 'visitor'), 'job.list_others')) {
		$res['errno'] = Code::NO_PRIVILEGE;
		return $res;
	}
	$res['jobs'] = JobManager::gets($rule);
	$res['count'] = JobManager::count($rule);
	$res['errno'] = $res['jobs'] === null ? Code::FAIL : Code::SUCCESS;
	return $res;
}

function job_describe(CRObject $rule)
{
	if (!AccessController::hasAccess(Session::get('role', 'visitor'), 'job.describe')) {
		$res['errno'] = Code::NO_PRIVILEGE;
		return $res;
	}
	$res['errno'] = Code::FAIL;
	$origin = JobManager::get($rule);
	if ($origin === null) {
		$res['errno'] = Code::RECORD_NOT_EXIST;
	} else if ($origin['created_by'] !== Session::get('uid') && !AccessController::hasAccess(Session::get('role', 'visitor'), 'job.describe_others')) {
		$res['errno'] = Code::NO_PRIVILEGE;
	}
	return $res;
}
