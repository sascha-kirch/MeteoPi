<?php	
   $connection = mysqli_connect("localhost", "meteopi","meteopi", "MeteoPiDatabase");
   // check connection
   if(mysqli_connect_errno()){
   	echo 'Failed to connect to MySQL:'. mysqli_connect_error();
   	exit();
   }
   ?>
<!DOCTYPE html>
<html lang="en-US">
   <head>
      <meta charset="UTF-8" />
      <meta name="description" content="MeteoPi Web Interface" />
      <meta name="keywords" content="Raspberry Pi, Arduino, MeteoPi" />
      <meta name="author" content="Sascha Kirch" />
      <meta http-equiv="refresh" content="300">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <base href="./" />
      <title>MeteoPi Web Interface: Dashboard</title>
      <script type="text/javascript">
      function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }

      function getCookie(cname) {
        let name = cname + "=";
        let ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
      }
      </script>
      <script type="text/javascript">
            //Cookie can be set by changing value from 0->1. If one, respective values are loaded and displayed on the website.
            setCookie("load_humidity","1",3)
            setCookie("load_temperature","1",3)
            setCookie("load_heatindex","1",3)
            setCookie("load_pressure","0",3)
            setCookie("load_lux","0",3)
            setCookie("load_eco","0",3)
            setCookie("load_tvoc","0",3)           
      </script>
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
         google.charts.load('current', {'packages':['corechart','annotationchart']});
           if(getCookie("load_humidity")=="1"){google.charts.setOnLoadCallback(drawChart_humidity);}
           if(getCookie("load_temperature")=="1"){google.charts.setOnLoadCallback(drawChart_temperature);}
           if(getCookie("load_pressure")=="1"){google.charts.setOnLoadCallback(drawChart_pressure);}
           if(getCookie("load_lux")=="1"){google.charts.setOnLoadCallback(drawChart_lux);}
           if(getCookie("load_eco")=="1"){google.charts.setOnLoadCallback(drawChart_eco2);}
           if(getCookie("load_tvoc")=="1"){google.charts.setOnLoadCallback(drawChart_tvoc);}
         
           
         function drawChart_humidity() {
         
               <?php	
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId,timeStamp, humidity FROM SensorMeasurement ORDER BY sensorMeasurementId DESC)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_humidity = google.visualization.arrayToDataTable([";
            echo"['Time','Room Humidity'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "[new Date('".$row['timeStamp']."'),".$row['humidity']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>
             
         
         var options_humidity = {
               title: 'Room Humidity [%]',
	       width:'95%',
          fill:10,
	       animation: {
	       startup: true,
	       duration: 3000,
	       easing: 'out',
	       },
	       legend:{
	       position: 'none'
	       },
	       colors: ['#83B7F2']
             };
         var chart_humidity = new google.visualization.AnnotationChart(document.getElementById('Chart_humidity'));
             
         chart_humidity.draw(data_humidity, options_humidity);
           }
           
           function drawChart_temperature() {
         
               <?php	
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, timeStamp, temperature,heatIndex FROM SensorMeasurement ORDER BY sensorMeasurementId DESC )var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_temperature = google.visualization.arrayToDataTable([";
            echo"['Time','Room Temperature','Sensational Temperature'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "[new Date('".$row['timeStamp']."'),".$row['temperature'].",".$row['heatIndex']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>
             
         
         var options_temperature = {
               title: 'Room Temperature [°C]',
	       width:'98%',
          fill:10,
	       animation: {
	       startup: true,
	       duration: 3000,
	       easing: 'out',
	       },
	       legend:{
	       position: 'none'
	       },
	       colors: ['#FA9343','#F58A8A']
             };
         var chart_temperature = new google.visualization.AnnotationChart(document.getElementById('Chart_temperature'));
             
         chart_temperature.draw(data_temperature, options_temperature);
           }
           
           function drawChart_pressure() {
         
               <?php	
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, timeStamp, pressure FROM SensorMeasurement ORDER BY sensorMeasurementId DESC )var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_pressure = google.visualization.arrayToDataTable([";
            echo"['Time','Pressure'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "[new Date('".$row['timeStamp']."'),".$row['pressure']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>
             
         
         var options_pressure = {
               title: 'Room Pressure [hPa]',
	       width:'95%',
          fill:10,
	       animation: {
	       startup: true,
	       duration: 3000,
	       easing: 'out',
	       },
	       legend:{
	       position: 'none'
	       },
	       colors: ['#A8ED8D']
             };
         var chart_pressure = new google.visualization.AnnotationChart(document.getElementById('Chart_pressure'));
             
         chart_pressure.draw(data_pressure, options_pressure);
           }
         
         function drawChart_lux() {
         
               <?php	
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, timeStamp, lux FROM SensorMeasurement ORDER BY sensorMeasurementId DESC )var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_lux = google.visualization.arrayToDataTable([";
            echo"['Time','Lumination'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "[new Date('".$row['timeStamp']."'),".$row['lux']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>
             
         
         var options_lux = {
               title: 'Room Lumination [lux]',
	       width:'95%',
          fill:10,
	       animation: {
	       startup: true,
	       duration: 3000,
	       easing: 'out',
	       },
	       legend:{
	       position: 'none'
	       },
	       colors: ['#ffcc00']
             };
         var chart_lux = new google.visualization.AnnotationChart(document.getElementById('Chart_lux'));
             
         chart_lux.draw(data_lux, options_lux);
           }
           
         function drawChart_eco2() {
         
               <?php	
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, timeStamp, eco2 FROM SensorMeasurement ORDER BY sensorMeasurementId DESC )var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_eco2 = google.visualization.arrayToDataTable([";
            echo"['Time','CO2 Conentration'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "[new Date('".$row['timeStamp']."'),".$row['eco2']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>
             
         
         var options_eco2 = {
               title: 'Room CO2 Conentration [ppm]',
	       width:'95%',
          fill:10,
	       animation: {
	       startup: true,
	       duration: 3000,
	       easing: 'out',
	       },
	       legend:{
	       position: 'none'
	       },
	       colors: ['#F890EC']
             };
         var chart_eco2 = new google.visualization.AnnotationChart(document.getElementById('Chart_eco2'));
             
         chart_eco2.draw(data_eco2, options_eco2);
           }  
           
           function drawChart_tvoc() {
         
               <?php	
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, timeStamp, tvoc FROM SensorMeasurement ORDER BY sensorMeasurementId DESC )var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_tvoc = google.visualization.arrayToDataTable([";
            echo"['Time','Total Volatile Organic Compound'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "[new Date('".$row['timeStamp']."'),".$row['tvoc']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>
             
         
         var options_tvoc = {
               title: 'Room Total Volatile Organic Compound [ppb]',
	       width:'95%',
          fill:10,
	       animation: {
	       startup: true,
	       duration: 3000,
	       easing: 'out',
	       },
	       legend:{
	       position: 'none'
	       },
	       colors: ['#35F32B']
             };
         var chart_tvoc = new google.visualization.AnnotationChart(document.getElementById('Chart_tvoc'));
             
         chart_tvoc.draw(data_tvoc, options_tvoc);
           } 
           
           
           jQuery(document).ready(function(){
            jQuery(window).resize(function(){
             drawChart_humidity();
             drawChart_temperature();
             drawChart_pressure();
             drawChart_lux();
             drawChart_eco2();
             drawChart_tvoc();
            });
           });
      </script>
      <script type="text/javascript">
         google.charts.load('current', {'packages':['gauge']});
           if(getCookie("load_humidity")=="1"){google.charts.setOnLoadCallback(drawGauge_humidity);}
           if(getCookie("load_temperature")=="1"){google.charts.setOnLoadCallback(drawGauge_temperature);}
           if(getCookie("load_heatindex")=="1"){google.charts.setOnLoadCallback(drawGauge_heatIndex);}
           if(getCookie("load_pressure")=="1"){google.charts.setOnLoadCallback(drawGauge_pressure);}
           if(getCookie("load_lux")=="1"){google.charts.setOnLoadCallback(drawGauge_lux);}
           if(getCookie("load_eco")=="1"){google.charts.setOnLoadCallback(drawGauge_eco2);}
           if(getCookie("load_tvoc")=="1"){google.charts.setOnLoadCallback(drawGauge_tvoc);}
           
          function drawGauge_humidity(){
                         
            <?php	
            
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, humidity FROM SensorMeasurement ORDER BY sensorMeasurementId DESC LIMIT 1)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_humidity = google.visualization.arrayToDataTable([";
            echo"['Label', 'Value'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "['Humid[%]',".$row['humidity']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>
            
             var options = {min: 0, max: 100, yellowFrom: 60, yellowTo: 80,
             redFrom: 80, redTo: 100, minorTicks: 5};
             
             var chart = new google.visualization.Gauge(document.getElementById('gauge_humidity'));

            chart.draw(data_humidity, options);
           }
           
           function drawGauge_temperature(){
                         
            <?php	
            
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, temperature FROM SensorMeasurement ORDER BY sensorMeasurementId DESC LIMIT 1)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_temperature = google.visualization.arrayToDataTable([";
            echo"['Label', 'Value'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "['Temp[°C]',".$row['temperature']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>

             var options = {min: -20, max: 60, yellowFrom: 33, yellowTo: 41,
             redFrom: 41, redTo: 60, minorTicks: 5};
             
             var chart = new google.visualization.Gauge(document.getElementById('gauge_temperature'));

            chart.draw(data_temperature, options);
            
            
           }
         
           function drawGauge_heatIndex(){
                         
            <?php	
            
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, heatIndex FROM SensorMeasurement ORDER BY sensorMeasurementId DESC LIMIT 1)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_heatIndex = google.visualization.arrayToDataTable([";
            echo"['Label', 'Value'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "['Sens[°C]',".$row['heatIndex']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>

             var options = {min: -20, max: 60, yellowFrom: 33, yellowTo: 41,
             redFrom: 41, redTo: 60, minorTicks: 5};
             
             var chart = new google.visualization.Gauge(document.getElementById('gauge_heatIndex'));

            chart.draw(data_heatIndex, options);
            
            
           }
           
           function drawGauge_pressure(){
                         
            <?php	
            
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, pressure FROM SensorMeasurement ORDER BY sensorMeasurementId DESC LIMIT 1)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_pressure = google.visualization.arrayToDataTable([";
            echo"['Label', 'Value'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "['Pres[hPa]',".$row['pressure']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>

             var options = {min: 900, max: 1100, minorTicks: 5};
             
             var chart = new google.visualization.Gauge(document.getElementById('gauge_pressure'));

            chart.draw(data_pressure, options);
            
            
           }
           
           function drawGauge_lux(){
                         
            <?php	
            
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, lux FROM SensorMeasurement ORDER BY sensorMeasurementId DESC LIMIT 1)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_lux = google.visualization.arrayToDataTable([";
            echo"['Label', 'Value'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "['Light[lux]',".$row['lux']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>

             var options = {min: 0, max: 1000, minorTicks: 5};
             
             var chart = new google.visualization.Gauge(document.getElementById('gauge_lux'));

            chart.draw(data_lux, options);
            
            
           }
           
           function drawGauge_eco2(){
                         
            <?php	
            
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, eco2 FROM SensorMeasurement ORDER BY sensorMeasurementId DESC LIMIT 1)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_eco2 = google.visualization.arrayToDataTable([";
            echo"['Label', 'Value'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "['CO2[ppm]',".$row['eco2']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>

             var options = {min: 0, max: 65535, minorTicks: 5};
             
             var chart = new google.visualization.Gauge(document.getElementById('gauge_eco2'));

            chart.draw(data_eco2, options);
            
            
           }
           
           function drawGauge_tvoc(){
                         
            <?php	
            
            $strSQL = "SELECT * FROM (SELECT sensorMeasurementId, tvoc FROM SensorMeasurement ORDER BY sensorMeasurementId DESC LIMIT 1)var1 ORDER BY sensorMeasurementId ASC";
            echo "var data_tvoc = google.visualization.arrayToDataTable([";
            echo"['Label', 'Value'],";	
            // Execute the query
            if($result = mysqli_query($connection,$strSQL)){
            
            while($row = mysqli_fetch_assoc($result)){
            echo "['tvoc[ppb]',".$row['tvoc']."],";
            }
            
            //free result set
            mysqli_free_result($result);
            } else {
            echo 'Could not run query: ' . mysqli_error();
            exit();
            }
            echo"]);";
            
            ?>

             var options = {min: 0, max: 65535, minorTicks: 5};
             
             var chart = new google.visualization.Gauge(document.getElementById('gauge_tvoc'));

            chart.draw(data_tvoc, options);
            
            
           }
           
      </script>
      <link rel="stylesheet" href="styleSheets/styles.css" />
   </head>
   <body>
      <div class="logo">
         <a href="index.php"><img src="Logo.png" alt="Logo"></a>
      </div>
      <nav>
         <ul>
            <li><a class="active" href="index.php">Dashboard</a></li>
         </ul>
      </nav>
      <div class="main">
         <table>
            <tr>
               <?php
                  if ($_COOKIE['load_humidity'] == "1"){
                        echo '<td><div id="gauge_humidity" class="chartGauge"></div></td>';
                  }
                  if ($_COOKIE['load_temperature'] == "1"){
                        echo '<td><div id="gauge_temperature" class="chartGauge"></div></td>';
                  }
                  if ($_COOKIE['load_heatindex'] == "1"){
                        echo '<td><div id="gauge_heatIndex" class="chartGauge"></div></td>';
                  }
                  if ($_COOKIE['load_pressure'] == "1"){
                        echo '<td><div id="gauge_pressure" class="chartGauge"></div></td>';
                  }
                  if ($_COOKIE['load_lux'] == "1"){
                        echo '<td><div id="gauge_lux" class="chartGauge"></div></td>';
                  }
                  if ($_COOKIE['load_eco'] == "1"){
                        echo '<td><div id="gauge_eco2" class="chartGauge"></div></td>';
                  }
                  if ($_COOKIE['load_tvoc'] == "1"){
                        echo '<td><div id="gauge_tvoc" class="chartGauge"></div></td>';
                  }
               ?>
            </tr>
         </table>
            <?php
            if ($_COOKIE['load_humidity'] == "1"){
                  echo '<div id="Chart_humidity" class="chartAnnotationChart"></div>';
            }
            if ($_COOKIE['load_temperature'] == "1"){
                  echo '<div id="Chart_temperature" class="chartAnnotationChart"></div>';
            }
            if ($_COOKIE['load_pressure'] == "1"){
                  echo '<div id="Chart_pressure" class="chartAnnotationChart"></div>';
            }
            if ($_COOKIE['load_lux'] == "1"){
                  echo '<div id="Chart_lux" class="chartAnnotationChart"></div>';
            }
            if ($_COOKIE['load_eco'] == "1"){
                  echo '<div id="Chart_eco2" class="chartAnnotationChart"></div>';
            }
            if ($_COOKIE['load_tvoc'] == "1"){
                  echo '<div id="Chart_tvoc" class="chartAnnotationChart"></div>';
            }
            ?>
      </div>
      <footer>
         <span><b>Mail:</b> <a href="mailto:skirch1@alumno.uned.es">skirch1@alumno.uned.es</a></span>
         <span><b>Creator:</b> "Sascha Kirch"</span>
         <span><b>MeteoPi Version:</b> "v1.1"</span>
      </footer>
   </body>
</html>
