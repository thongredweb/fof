#!/bin/sh

if [ "$1" ]
then
	if [ $1 == 'native' ]
	then
		../vendor/bin/phpunit-randomizer -c ../phpunit.xml ../Tests/
	else
		../vendor/bin/phpunit-randomizer --order seed:$1 -c ../phpunit.xml ../Tests/
	fi
else
	../vendor/bin/phpunit-randomizer --order rand -c ../phpunit.xml ../Tests/
fi
