#! /usr/bin/env bash

# Largo directory name
export SLUG=$(basename $(pwd))

# Where we install the test content
export WP_TESTS_DIR=/tmp/wordpress/tests/phpunit

# Where WordPress is installed for running the tests
export WP_CORE_DIR=/tmp/wordpress/

# WordPress version
export WP_VERSION="6.1.1"

# DB connection info
export DB_USER="wordpress"
export DB_PASSWORD="password"

git clone --depth=1 --branch="$WP_VERSION" git://develop.git.wordpress.org/ $WP_CORE_DIR

pushd ..
cp -r $SLUG "$WP_CORE_DIR/src/wp-content/themes/$SLUG"
pushd $WP_CORE_DIR
mysql -e "CREATE DATABASE wordpress_tests;" -u$DB_USER -p$DB_PASSWORD
cp wp-tests-config-sample.php wp-tests-config.php
sed -i "s:dirname( __FILE__ ) . '/src/':'$WP_CORE_DIR/src/':" wp-tests-config.php
sed -i "s/youremptytestdbnamehere/wordpress_tests/" wp-tests-config.php
sed -i "s/yourusernamehere/wordpress/" wp-tests-config.php
sed -i "s/yourpasswordhere/password/" wp-tests-config.php
mv wp-tests-config.php "$WP_TESTS_DIR/wp-tests-config.php"
pushd "$WP_CORE_DIR/src/wp-content/themes/$SLUG"