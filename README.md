# Temp Monitor
Internet of Things data platform for temperature and humidity sensors with maps

## Table of contents
  * [Demo](#demo)
  * [License](#license)
  * [Technology stack](#technology-stack)
  * [Features](#features)
    * [Overview](#overview)
    * [Sensors list](#sensors-list)
    * [Sensor monitor](#sensor-monitor)
      * [Live](#live)
      * [24 hours](#24-hours)
      * [30 days](#30-days)
    * [Sensor settings](#sensor-settings)
    * [Sensor API](#sensor-api)
    * [Maps](#maps)
      * [Google Maps](#google-maps)
      * [Custom maps (Default)](#custom-maps-default)
      * [Custom maps (Heatmap)](#custom-maps-heatmap)
  * [How to deploy](#how-to-deploy)
    * [Requirements](#requirements)
    * [Database](#database)
    * [Composer](#composer)
    * [Permissions](#permissions)


## Demo
Visit [tempmonitor.silviosimunic.com](http://tempmonitor.silviosimunic.com) for demo.

## License
See [LICENSE](https://github.com/ssimunic/Temp-Monitor/blob/master/LICENSE).

## Technology stack
* [Laravel](https://laravel.com/) for website and RESTful API
* [MySQL](https://www.mysql.com/) database

This project is also using [jQuery](https://jquery.com/), [Bootstrap](http://getbootstrap.com/) and [Google Maps API](https://developers.google.com/maps/).

Theme used is [Bootswatch Paper](https://bootswatch.com/paper/). 

Charts are powered by [Highcharts](http://www.highcharts.com/). See their [License/Pricing](https://shop.highsoft.com/highcharts).

Heatmaps are generated with [heatmap.js](https://www.patrick-wied.at/static/heatmapjs/).

## Features
### Overview
Home page when user is logged in.

![](http://i.imgur.com/GJCJ37s.png)

### Sensors list
List of sensors with latest data and action menu.

![](http://i.imgur.com/SyRgTN7.png)

### Sensor monitor
#### Live
Displays temperature and humidity line chart with live data.

![](http://i.imgur.com/O1InGkm.png)

#### 24 hours
Displays temperature line chart (average and range) for last 24 hours.

![](http://i.imgur.com/FC7EpI0.png)

#### 30 days
Displays temperature line chart (average and range) for last 30 days.

### Sensor settings
Change sensor settings and alerts.

![](http://i.imgur.com/dYZAet6.png)

### Sensor API
Quick guide on how to use RESTful API for selected sensor.

![](http://i.imgur.com/uKWu0Du.png)

### Maps
#### Google Maps
Basic interactive Google Map with sensor data.

![](http://i.imgur.com/2UjlBW4.jpg)

#### Custom maps (Default)
Custom map with background.

![](http://i.imgur.com/LoBJ5Ly.png)

#### Custom maps (Heatmap)
Custom map with background and heatmap.

![](http://i.imgur.com/9ZYkpMY.png)

## How to deploy

### Requirements
* Apache 2
* PHP 5.6
* MySQL
* Composer

### Database
Download ```structure.sql``` file and execute SQL.

```
root@server:~$ mysql -u root -p

mysql> source path/to/structure.sql
```

This will create ```tempmonitor``` database and tables.

Configure MySQL settings in ```.env``` and ```config/database.php``` files.

### Composer

Run ```composer install``` inside project folder to install dependencies.

### Permissions

If you are getting 500 error at this point, run

```
sudo chmod 755 -R project_folder

chmod -R o+w project_folder/storage project_folder/public/uploads
```
