<?php
    include 'db_connection.php';
    
    
    /**
    * Connects to the mysql database 
    * and shows the contents of the table theValues in command line
    */
    function showTable(){
        
        $conn = OpenCon();
        
        $sql="SELECT aValue, aTimestamp FROM theValues";
        $result = $conn->query($sql);
        
        /* fetch object array */
        while ($row = $result->fetch_row()) {
        printf("Value: %s, Timestamp: (%s)\n", $row[0], $row[1]);
        }

        CloseCon($conn);
        
    }

    /** 
    * Receives as parameters an int called value and an int called timestamp
    * connects to the mysql database 
    * inserts a value and a timestamp in the table theValues
    * and shows in the command line that a new record created 
    * in the table theValues
    * @param $value,$timestamp 
    */
    function insertToTable($value,$timestamp){
            $conn = OpenCon();
            
            $sql = "INSERT INTO theValues (aValue, aTimestamp)
             VALUES ('$value','$timestamp')";
            $result = $conn->query($sql);

            if ($result === TRUE) {
                echo "New record created successfully\n";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            CloseCon($conn);
    }

    

?>