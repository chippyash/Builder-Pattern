#!/bin/bash
cd ~/Projects/chippyash/source/Builder-Pattern
vendor/bin/phpunit -c test/phpunit.xml --testdox-html contract.html test/
tdconv -t "Chippyash Builder Pattern" contract.html docs/Test-Contract.md
rm contract.html

