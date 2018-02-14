#!/bin/bash

# Exit if anything fails AND echo each command before executing
# http://www.peterbe.com/plog/set-ex
set -ex

NAP_LENGTH=1

###########################################################
# install npm dependencies to build assets via yarn.
yarn install && yarn run assets


###########################################################
# WordPress.
# Download.
mkdir -p $WORDPRESS_DIR
vendor/bin/wp core download --force --version=$WORDPRESS_VERSION --path=$WORDPRESS_DIR

# Create config.
rm -f ${WORDPRESS_DIR}wp-config.php
vendor/bin/wp core config --path=$WORDPRESS_DIR --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASS --dbhost=$DB_HOST

# Install.
vendor/bin/wp db create --path=$WORDPRESS_DIR
vendor/bin/wp core install --path=$WORDPRESS_DIR --url=$WORDPRESS_URL --title="wordpress.dev" --admin_user="admin" --admin_password="admin" --admin_email="admin@wp.dev"


###########################################################
# Selenium
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

# Install for Travis the Joomla Selenium Server Standalone via Composer.
composer require joomla-projects/selenium-server-standalone

php -r 'require "vendor/joomla-projects/selenium-server-standalone/Selenium.php";
$selenium = new Selenium(["browser" => "chrome", "selenium_params" => [" -Dselenium.LOGGER.level=OFF"] ]);
$selenium->run();';

wait_for_port
sleep 5


###########################################################
# Server
# start webserver.
php -S "$WORDPRESS_URL" -t "$WORDPRESS_DIR" >/dev/null 2>&1 &

# symlink the plugin in the WordPress plugin folder
ln -s $TRAVIS_BUILD_DIR $WORDPRESS_DIR/wp-content/plugins/inpsyde-google-tag-manager

# show the plugins folder contents to make sure the plugin folder is there
ls $WORDPRESS_DIR/wp-content/plugins


###########################################################
# Run behat
vendor/bin/behat

###########################################################
# Tidy up after test run.
# See https://github.com/travis-ci/travis-ci/issues/6861
kill -9 $(ps aux | grep 'selenium' | awk '{print $2}')
kill -9 $(ps aux | grep 'java' | awk '{print $2}')
kill -9 $(ps aux | grep 'Xvfb' | awk '{print $2}')