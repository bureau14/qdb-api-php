<?php

$PROJECT_ROOT = dirname(__FILE__) . '/../../../..';

$DAEMON_FLAGS = "--transient --log-console --address 127.0.0.1:20552";

echo 'Check daemon... ';
if (is_executable('qdbd')) {
    $DAEMON = 'qdbd';
} else if (is_executable('qdbdd')) {
    $DAEMON = 'qdbdd';
} else if (is_executable('qdbd.exe')) {
    $DAEMON = 'qdbd.exe';
} else if (is_executable('qdbdd.exe')) {
    $DAEMON = 'qdbdd.exe';
} else {
    echo 'Failed (cwd=', getcwd(), ')', PHP_EOL;
    exit(1);
}
echo 'OK', PHP_EOL;

echo 'Check extension... ';
if (!extension_loaded('qdb')) {
    echo "Failed", PHP_EOL;
    exit(1);
}
echo 'OK', PHP_EOL;

echo 'Starting qdb daemon... ';

$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin
    1 => array("file", "daemon-stdout.txt", "w"),
    2 => array("file", "daemon-stderr.txt", "w")
);

$cmdline = '"' . realpath($DAEMON) . '" ' . $DAEMON_FLAGS;
$process = proc_open($cmdline, $descriptorspec, $pipes);
sleep(5);
$status = proc_get_status($process);
if (!$status['running']) {
    echo 'Failed', PHP_EOL;
    echo file_get_contents("daemon-stdout.txt");
    echo file_get_contents("daemon-stderr.txt");
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
