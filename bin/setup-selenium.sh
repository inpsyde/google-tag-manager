#!/bin/bash

# Exit if anything fails AND echo each command before executing
# http://www.peterbe.com/plog/set-ex
set -ex

NAP_LENGTH=1

# Wait for a specific port to respond to connections.
wait_for_port() {
  while echo | telnet localhost 4444 2>&1 | grep -qe 'Connection refused'; do
    echo "Connection refused on port 4444. Waiting $NAP_LENGTH seconds..."
    sleep $NAP_LENGTH
  done
}

export DISPLAY=:99.0
sh -e /etc/init.d/xvfb start
sleep 1

php -r 'require "vendor/joomla-projects/selenium-server-standalone/Selenium.php";
$selenium = new Selenium(["browser" => "chrome", "selenium_params" => [" -Dselenium.LOGGER.level=OFF"] ]);
$selenium->run();';

wait_for_port
sleep 5