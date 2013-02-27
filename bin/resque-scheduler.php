#!/usr/bin/env php
<?php
// Look for an environment variable with
$RESQUE_PHP = getenv('RESQUE_PHP');
if (!empty($RESQUE_PHP)) {
    require_once $RESQUE_PHP;
    require_once dirname($RESQUE_PHP) . '/Resque/Worker.php';
} elseif (!class_exists('Resque')) { // Otherwise, if we have no Resque then assume it is in the include path
    require_once 'Resque/Resque.php';
}

// Load resque-scheduler
require_once dirname(dirname(__FILE__)) . '/lib/ResqueScheduler/ResqueScheduler.php';
require_once dirname(dirname(__FILE__)) . '/lib/ResqueScheduler/Worker.php';

$REDIS_BACKEND = getenv('REDIS_BACKEND');
$REDIS_DATABASE = getenv('REDIS_DATABASE');
$REDIS_NAMESPACE = getenv('REDIS_NAMESPACE');

$LOG_HANDLER = getenv('LOGHANDLER');
$LOG_HANDLER_TARGET = getenv('LOGHANDLERTARGET');

$logger = new MonologInit\MonologInit($LOG_HANDLER, $LOG_HANDLER_TARGET);

if (!empty($REDIS_BACKEND)) {
    Resque::setBackend($REDIS_BACKEND, $REDIS_DATABASE, $REDIS_NAMESPACE);
}

// Set log level for resque-scheduler
$logLevel = 0;
$LOGGING = getenv('LOGGING');
$VERBOSE = getenv('VERBOSE');
$VVERBOSE = getenv('VVERBOSE');
if (!empty($LOGGING) || !empty($VERBOSE)) {
    $logLevel = ResqueScheduler\Worker::LOG_NORMAL;
} elseif (!empty($VVERBOSE)) {
    $logLevel = ResqueScheduler\Worker::LOG_VERBOSE;
}

// Load the user's application if one exists
$APP_INCLUDE = getenv('APP_INCLUDE');
if ($APP_INCLUDE) {
    if (!file_exists($APP_INCLUDE)) {
        die('APP_INCLUDE ('.$APP_INCLUDE.") does not exist.\n");
    }

    require_once $APP_INCLUDE;
}

// Check for jobs every $interval seconds
$interval = 5;
$INTERVAL = getenv('INTERVAL');
if (!empty($INTERVAL)) {
    $interval = $INTERVAL;
}

$worker = new ResqueScheduler\Worker(ResqueScheduler\ResqueScheduler::QUEUE_NAME);
$worker->registerLogger($logger);
$worker->logLevel = $logLevel;

$PIDFILE = getenv('PIDFILE');
if ($PIDFILE) {
    file_put_contents($PIDFILE, getmypid()) or
        die('Could not write PID information to ' . $PIDFILE);
}

logStart($logger, array('message' => '*** Starting scheduler worker ' . $worker, 'data' => array('type' => 'start', 'worker' => (string) $worker)), $logLevel);
$worker->work($interval);

function logStart($logger, $message, $logLevel)
{
    if ($logger === null || $logger->getInstance() === null) {
        fwrite(STDOUT, (($logLevel == Resque_Worker::LOG_NORMAL) ? "" : "[" . strftime('%T %Y-%m-%d') . "] ") . $message['message'] . "\n");
    } else {
        list($host, $pid, $queues) = explode(':', $message['data']['worker'], 3);
        $message['data']['worker'] = $host . ':' . $pid;
        $message['data']['queues'] = explode(',', $queues);

        $logger->getInstance()->addInfo($message['message'], $message['data']);
    }
}
