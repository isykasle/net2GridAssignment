<?php

include_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//diavazw tis metavlites apo to .env arxeio
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// sindesi me rabbitMQ
$connection = new AMQPStreamConnection(
    $_ENV["MESSAGEQUEUE_HOSTNAME"],
    5672,
    $_ENV["MESSAGEQUEUE_USERNAME"],
    $_ENV["MESSAGEQUEUE_PASSWORD"]
);
$channel = $connection->channel();


//Kanw HTTP Request sto url
$json = file_get_contents($_ENV['API_URL']);
$data = json_decode($json);

echo(json_encode($data, JSON_PRETTY_PRINT));
echo("\n");

// Spaw ta dedomena se metavlites
$gatewayEui = $data->{'gatewayEui'};
$profileId = $data->{'profileId'};
$endpointId = $data->{'endpointId'};
$clusterId = $data->{'clusterId'};
$attributeId = $data->{'attributeId'};
$value = $data->{'value'};
$timestamp = $data->{'timestamp'};

//ftiaxnw to routing key <gateway eui>.<profile>.<endpoint>.<cluster>.<attribute>
//metatrepw tis times apo hex se dekadiko sistima
$routingKey = hexToDecimal($gatewayEui) . '.' . hexToDecimal($profileId) . '.' . hexToDecimal($endpointId) . '.' .
    hexToDecimal($clusterId) . '.' . hexToDecimal($attributeId);

echo($routingKey);
echo("\n");
//ftiaxnw to value to opoio tha mpei sto rabbitmq(mesa sto keli)
$payload = json_encode([
    'value'=>$value,
    'timestamp'=>$timestamp
],JSON_PRETTY_PRINT);


//stelnw sto rabbitmq to minima ston solina kai sto domatio(keli) poy prepei
//Stelnw ston solina me
// onoma $_ENV['MESSAGEQUEUE_EXCHANGE'] kai sto domatio me
// onoma $routingKey tin
// timi $messageForQueue
$messageForQueue = new AMQPMessage($payload);
$channel->basic_publish($messageForQueue, $_ENV['MESSAGEQUEUE_EXCHANGE'], $routingKey);


/**
 * Pairnei san parametro ena dekaexadiko arithmo kai ton metatrepei se dekadiko
 * @param $hexString
 * @return string
 */
 function hexToDecimal($hexString): string
{
    return base_convert($hexString, 16, 10);
}

$channel->close();
$connection->close();

?>
