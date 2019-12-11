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
