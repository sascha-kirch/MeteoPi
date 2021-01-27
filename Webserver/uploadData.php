<?php
	include('config.php');
    $conn =  mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD,DB_NAME) or die("Unable to connect to MySQL");
    //0: sensorMeasurementId -> NULL
    //1: timeStamp
    //2: temperature
    //3: humidity
    //4: heatIndex
    //5: preasure
    //6: clientMacAddressRefference

	$sensorMeasurementId="NULL";
	
    if (mysqli_real_escape_string($conn,$_POST['temperature']) ==NULL ||mysqli_real_escape_string($conn,$_POST['temperature']) ==NAN){
        $temperature="NULL";
    }else{
        $temperature=mysqli_real_escape_string($conn,$_POST['temperature']);
    }
    if (mysqli_real_escape_string($conn,$_POST['humidity']) ==NULL){
        $humidity="NULL";
    }else{
        $humidity=mysqli_real_escape_string($conn,$_POST['humidity']);
    }
    if (mysqli_real_escape_string($conn,$_POST['heatIndex']) ==NULL){
        $heatIndex="NULL";
    }else{
        $heatIndex=mysqli_real_escape_string($conn,$_POST['heatIndex']);
    }
    if (mysqli_real_escape_string($conn,$_POST['pressure']) ==NULL){
        $pressure="NULL";
    }else{
        $pressure=mysqli_real_escape_string($conn,$_POST['pressure']);
    }
    if (mysqli_real_escape_string($conn,$_POST['lux']) ==NULL){
        $lux="NULL";
    }else{
        $lux=mysqli_real_escape_string($conn,$_POST['lux']);
    }
    if (mysqli_real_escape_string($conn,$_POST['eco2']) ==NULL){
        $eco2="NULL";
    }else{
        $eco2=mysqli_real_escape_string($conn,$_POST['eco2']);
    }
    if (mysqli_real_escape_string($conn,$_POST['tvoc']) ==NULL){
        $tvoc="NULL";
    }else{
        $tvoc=mysqli_real_escape_string($conn,$_POST['tvoc']);
    }
    if (mysqli_real_escape_string($conn,$_POST['clientMacAddressRefference']) ==NULL){
        $clientMacAddressRefference="NULL";
    }else{
        $clientMacAddressRefference=mysqli_real_escape_string($conn,$_POST['clientMacAddressRefference']);
    }

    $timeStamp= date("Y-m-d H:i:s");

    $insertSQL="INSERT into ".TB_ENV." (sensorMeasurementId,timeStamp,temperature,humidity,heatIndex, pressure, lux, eco2, tvoc, clientMacAddressRefference) values (".$sensorMeasurementId.",'".$timeStamp."',".$temperature.",".$humidity.",".$heatIndex.",".$pressure.",".$lux.",".$eco2.",".$tvoc.",'".$clientMacAddressRefference."')";
    mysqli_query($conn,$insertSQL) or die("INSERT Query has Failed - ".$insertSQL );

?>
