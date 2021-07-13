The test of the project has been done in Windows OS.

To set the project follow the below instructions:

Firstly,Download and Install in your system the PHP 8.0 (8.0.8) 
programming language.
Secondly,Download and Install composer a php open source library 
which is named Composer(version 2.1.3).
After,Include the folder/project net2GridAssignment in the folder 
where PHP is installed (e.g C:\PHP).
To continue,Open a command line,navigate to the folder of the project 
and run "composer install".By doing this,all libraries that are required 
are installing or updating.
(e.g cd C:\PHP\net2GridAssignment and after run composer install)

Then open the file named php.ini with a text editor 
which is located in the folder where PHP is installed(e.g C:\PHP).
In the file php.ini uncomment the extension=sockets 
and the extension=mysqli .

Finally the project requires to use a .env file.The file ".env.sample" should be renamed to ".env" .
In the file ".env" must be included the API's URL,the message queue details and database details.  

To run the project follow the below instructions:

In a terminal run the consumer(receiver) "php receive.php"
then, run the publisher (sender) "php send.php"

The consumer will print the message it gets from the sender via RabbitMQ and 
insert the message in a table called theValues 
with three columns an id,aValue,aTimestamp of database "cand_swc". 
The receiver will keep running, waiting for messages (Use Ctrl-C to stop it),so try running the sender from another terminal.

