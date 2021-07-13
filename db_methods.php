<?php
    include 'db_connection.php';
    
    
    /*
    $json = file_get_contents($_ENV['MESSAGEQUEUE_RESULTS_QUEUE']);
    $data = json_decode($json);

    $value=$data->{'value'};
    $timestamp=$data->{'timestamp'};
    */
    
    


    function showTable(){
        
        $conn = OpenCon();

        
        $sql="SELECT aValue, aTimestamp FROM theValues";
        $result = $conn->query($sql);

/* fetch object array */
    while ($row = $result->fetch_row()) {
    
        //echo "id: " . $row[0]. " - Name: " . $row[1].  "<br>";
        printf("Value: %s, Timestamp: (%s)\n", $row[0], $row[1]);
    }
    CloseCon($conn);
        
        
        


    }


        

    function insertToTable($value,$timestamp){
            $conn = OpenCon();
            
            $sql = "INSERT INTO theValues (aValue, aTimestamp)
             VALUES ('$value','$timestamp')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully\n";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            CloseCon($conn);
    }

    

?>