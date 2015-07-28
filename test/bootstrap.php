<?php

$PROJECT_ROOT = dirname(__FILE__) . '/../../../..';

$DAEMON_FLAGS = "--transient --address 127.0.0.1:20552";

echo 'Check extension... ';
if (!extension_loaded('quasardb')) {
    echo "Failed", PHP_EOL;
    exit(1);
}
echo 'OK', PHP_EOL;

echo 'Starting qdb daemon... ';

$daemon_stdout = tempnam(sys_get_temp_dir(), 'qdbd-stdout');
$daemon_stderr = tempnam(sys_get_temp_dir(), 'qdbd-stderr');

$descriptorspec = array(
    0 => array('pipe', 'r'),  // stdin
    1 => array('file', $daemon_stdout, 'w'),
    2 => array('file', $daemon_stderr, 'w')
);

$process = proc_open("qdbd $DAEMON_FLAGS", $descriptorspec, $pipes);
sleep(4);
$status = proc_get_status($process);
if (!$status['running']) {
    echo 'Failed', PHP_EOL;
    echo file_get_contents($daemon_stdout);
    echo file_get_contents($daemon_stderr);
    exit(2);
}
echo 'OK, pid=', $status['pid'], PHP_EOL;

function kill($pid) {
    if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
        return exec("taskkill /F /T /PID $pid");
    } else {
        return exec("pkill -KILL -P $pid") == 0 | exec("kill -9 $pid") == 0;
    }
}

register_shutdown_function(
    function () use ($process)
    {
        $pid = proc_get_status($process)['pid'];
        echo "Stopping qdb daemon (pid=$pid)... ";
        if (!kill($pid)) {
            echo "Failed", PHP_EOL;
            exit(3);
        }
        echo 'OK', PHP_EOL;
    });
?>
