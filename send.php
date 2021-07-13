<?php

include_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//read the values of variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

//connection with rabbitMQ
$connection = new AMQPStreamConnection(
    $_ENV["MESSAGEQUEUE_HOSTNAME"],
    5672,
    $_ENV["MESSAGEQUEUE_USERNAME"],
    $_ENV["MESSAGEQUEUE_PASSWORD"]
);
$channel = $connection->channel();


//make HTTP Request to url
$json = file_get_contents($_ENV['API_URL']);
$data = json_decode($json);

echo(json_encode($data, JSON_PRETTY_PRINT));
echo("\n");

// break the the json form of data to unique variables
$gatewayEui = $data->{'gatewayEui'};
$profileId = $data->{'profileId'};
$endpointId = $data->{'endpointId'};
$clusterId = $data->{'clusterId'};
$attributeId = $data->{'attributeId'};
$value = $data->{'value'};
$timestamp = $data->{'timestamp'};

//make the routing key <gateway eui>.<profile>.<endpoint>.<cluster>.<attribute>
//convert values from hex to decimal system
$routingKey = hexToDecimal($gatewayEui) . '.' . hexToDecimal($profileId) . '.' . hexToDecimal($endpointId) . '.' .
    hexToDecimal($clusterId) . '.' . hexToDecimal($attributeId);

echo($routingKey);
echo("\n");
//define the value that inserts in cell of rabbitMQ queue 
$payload = json_encode([
    'value'=>$value,
    'timestamp'=>$timestamp
],JSON_PRETTY_PRINT);



//send the message to the rabbitMQ queue and to the specific cell that is required
// Send to the rabbitMQ queue with name
// $_ENV['MESSAGEQUEUE_EXCHANGE'] 
// and to the cell with name $routingKey
// the value $messageForQueue
$messageForQueue = new AMQPMessage($payload);
$channel->basic_publish($messageForQueue, $_ENV['MESSAGEQUEUE_EXCHANGE'], $routingKey);


/**
 * Rexeives as parameter a hexademical number and return a decimal
 * @param $hexString
 * @return string
 */
 function hexToDecimal($hexString): string
{
    return base_convert($hexString, 16, 10);
}

//close the channel and connection of rabbitMQ queue
$channel->close();
$connection->close();

?>
