<?php
session_start();
// used to connect to the database
$conn=mysqli_connect("localhost","root","","todoapp");
if(!$conn){
    echo ("Connection failed");
}
// else{
//     echo "Connected successfully";
// }


?>