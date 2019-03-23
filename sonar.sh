#!/usr/bin/env bash

SONAR_CLI_ZIP=sonar-scanner-cli-3.3.0.1492-linux.zip
SONAR_CLI_DIR=sonar-scanner-3.3.0.1492-linux
SONAR_DIR=$HOME/.sonar-cli
SONAR_CLI_BIN=$SONAR_DIR/$SONAR_CLI_DIR/bin

if [[ ! -f $SONAR_CLI_BIN/sonar-scanner ]]; then
    echo Cache not found, dowloading Sonar CLI...
    rm -rf $SONAR_DIR/$SONAR_CLI_DIR
    wget -q https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/$SONAR_CLI_ZIP
    unzip -q $SONAR_CLI_ZIP
    mv $SONAR_CLI_DIR $SONAR_DIR/
fi

echo "TRAVIS_BRANCH=$TRAVIS_BRANCH"
echo "TRAVIS_TAG=$TRAVIS_TAG"
echo "TRAVIS_PULL_REQUEST_BRANCH=$TRAVIS_PULL_REQUEST_BRANCH"
echo "TRAVIS_PULL_REQUEST=$TRAVIS_PULL_REQUEST"
echo "TRAVIS_PULL_REQUEST_SLUG=$TRAVIS_PULL_REQUEST_SLUG"

if [[ $TRAVIS_PULL_REQUEST == 'false' ]]; then
    $SONAR_CLI_BIN/sonar-scanner \
        -Dsonar.projectKey=$SONARCLOUD_PROJECT \
        -Dsonar.organization=$SONARCLOUD_ORGANIZATION \
        -Dsonar.sources=src \
        -Dsonar.host.url=$SONARCLOUD_URL \
        -Dsonar.login=$SONARCLOUD_LOGIN \
        -Dsonar.exclusions=logs/**,vendor/** \
        -Dsonar.php.tests.reportPath=logs/phpunit/test-report.xml \
        -Dsonar.php.coverage.reportPaths=logs/phpunit/clover.xml \
        -Dsonar.branch.name=$TRAVIS_BRANCH \
        -Dsonar.projectVersion=$TRAVIS_TAG
else
    $SONAR_CLI_BIN/sonar-scanner \
        -Dsonar.projectKey=$SONARCLOUD_PROJECT \
        -Dsonar.organization=$SONARCLOUD_ORGANIZATION \
        -Dsonar.sources=src \
        -Dsonar.host.url=$SONARCLOUD_URL \
        -Dsonar.login=$SONARCLOUD_LOGIN \
        -Dsonar.exclusions=logs/**,vendor/** \
        -Dsonar.php.tests.reportPath=logs/phpunit/test-report.xml \
        -Dsonar.php.coverage.reportPaths=logs/phpunit/clover.xml \
        -Dsonar.pullrequest.branch=$TRAVIS_PULL_REQUEST_BRANCH \
        -Dsonar.pullrequest.key=$TRAVIS_PULL_REQUEST \
        -Dsonar.pullrequest.base=$TRAVIS_BRANCH \
        -Dsonar.pullrequest.github.repository=$TRAVIS_PULL_REQUEST_SLUG
fi

