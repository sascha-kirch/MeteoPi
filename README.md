<img src="https://github.com/SaKi1309/MeteoPi/blob/main/Webserver/Logo.png" width="200" />

# MeteoPi
MeteoPi is a weather station based on the ESP32 with a webserver running on a RaspberryPi.

## Setup
<img src="https://github.com/SaKi1309/MeteoPi/blob/main/imgs/IMG_3641.jpg" width="400" />


## Webinterface

### Webserver and Database
A database on a RaspberryPi is used to store information gathered by the sensors.
The following tools are used:

Component | Used Tool / Package | Installation commadn
------------ | ------------- | -------------
Webserver | Apache2 | ``` sudo apt install apache2 -y ```
PHP| php 7.3 | ``` sudo apt install php7.3 php7.3-mbstring php7.3-mysql php7.3-curl php7.3-gd php7.3-zip -y ```
Database | MariaDB 10 | ``` sudo apt-get install mariadb-client mariadb-server ```
Database Management | PHPMyAdmin | ``` sudo apt-get install phpmyadmin ```

### Webinterface
<img src="https://github.com/SaKi1309/MeteoPi/blob/main/imgs/meteopi_graph.PNG" width="1000" />

> Hint: the noise seen on the measurements is due to the compact and unoptimized layout of the setup.
