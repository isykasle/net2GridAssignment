<?php
include_once __DIR__ . '/vendor/autoload.php';

include_once 'db_methods.php';

use Dotenv\Dotenv;
use PhpAmqpLib\Connection\AMQPStreamConnection;

//diavazw tis metavlites apo to .env arxeio
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// sindesi me rabbitMQ
$connection = new AMQPStreamConnection(
    $_ENV['MESSAGEQUEUE_HOSTNAME'],
    5672,
    $_ENV['MESSAGEQUEUE_USERNAME'],
    $_ENV['MESSAGEQUEUE_PASSWORD']
);
$channel = $connection->channel();


//function pou kaleite kathe fora pou pernw dedomena apo ton solina
$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    //ta vazw sti mysql
    $data = json_decode($msg->body);
    $value=$data->{'value'};
    $timestamp=$data->{'timestamp'};
    //printf("%s (%s)\n", $value, $timestamp);
    insertToTable($value,$timestamp);
    showTable();
    //sleep(substr_count($msg->body, '.'));

    

};



//arxizw na pernw dedomena apo ton solina me
// onoma $_ENV['MESSAGEQUEUE_RESULTS_QUEUE']
$channel->basic_consume($_ENV['MESSAGEQUEUE_RESULTS_QUEUE'], '', false, true, false, false, $callback);

//lew sto programma na min kleisei oso perimenw minimata apo ton solina
while ($channel->is_open()) {
    $channel->wait();
}



?>
