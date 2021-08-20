#!/bin/bash
service ssh start &
nginx &
service php7.4-fpm start
