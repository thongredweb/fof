#!/bin/sh
php `pwd`/../vendor/phpunit/phpunit/phpunit -c configuration.xml "$@" .
