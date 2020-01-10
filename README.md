# hue

## Description
Links RESTful interface of motion sensors to Philips Hue API for controlling bulbs based on motion.

## Detail
Currently - index.php builds a webpage that displays movements and calls various external php files to trigger database writes or trigger light switches based on movement detected.

## ToDo Deployment
- [ ] Remove dependency on mixed JS and PHP files - make it pure JS
- [ ] Dockerize deployment as Nodejs application

## ToDo features
- [ ] Add remaining bulbs/rooms in house
- [ ] Add time of day considerations (no need to turn on lights at midday)
- [ ] Someones home when they're not capability

## Hacky notes

crontab -e output:
\* \* \* \* \* php /var/www/html/lights/roomsweeper.php
14 * * * * /usr/sbin/logrotate /root/logrotate.conf --state /root/logrotate-state

runs roomsweeper every minute which enables light off commands at set frequency
logrotate command needed because log files were getting huge

still need to write command to clear down database
