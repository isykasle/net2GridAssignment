<?php
include_once __DIR__ . '/vendor/autoload.php';

include_once 'db_methods.php';

use Dotenv\Dotenv;
use PhpAmqpLib\Connection\AMQPStreamConnection;

//read the values of variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

//connection with rabbitMQ
$connection = new AMQPStreamConnection(
    $_ENV['MESSAGEQUEUE_HOSTNAME'],
    5672,
    $_ENV['MESSAGEQUEUE_USERNAME'],
    $_ENV['MESSAGEQUEUE_PASSWORD']
);
$channel = $connection->channel();


/**
  *Receives the data of value and timestamp in a json format.
  *This function is called every time that I receive data from the RabbitMQ
  *queue.
  *Prints the data value and timestamp in command line
  *Inserts data to the table of a database
*/ 
$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";

    $data = json_decode($msg->body);
    $value=$data->{'value'};
    $timestamp=$data->{'timestamp'};
    insertToTable($value,$timestamp);
    //showTable();
    
};




//start to receive data from the queue with
//name $_ENV['MESSAGEQUEUE_RESULTS_QUEUE']
$channel->basic_consume($_ENV['MESSAGEQUEUE_RESULTS_QUEUE'], '', false, true, false, false, $callback);

//require the programm not to stop as I wait messages from the queue
while ($channel->is_open()) {
    $channel->wait();
}

?>
