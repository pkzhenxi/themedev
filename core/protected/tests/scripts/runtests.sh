#!/bin/bash

SCRIPT_PATH=`cd \`dirname $0\`; pwd`
cd "$SCRIPT_PATH/../unit"
phpunit --log-junit "$SCRIPT_PATH/../../../../test-reports/phpunit.xml" --stderr -c ../phpunitall-taxout.xml > output.log

exit 0