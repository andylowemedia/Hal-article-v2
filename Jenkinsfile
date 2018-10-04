pipeline {
  agent {
    docker {
        image '540688370389.dkr.ecr.eu-west-1.amazonaws.com/low-emedia/php:latest'
        args '-p 9000:9000'
    }
  }
  environment {
    CI = 'true'
  }
  stages {
    stage('build') {
      steps {
        sh '''#!/bin/bash

            echo "Build script"
            mkdir -p /var/coverage/reports
            docker run -v /var/coverage/reports:/var/www/html/public/coverage
            cd appcode
            composer install
            vendor/bin/phpunit --coverage-clover "/var/www/html/public/coverage/coverage.xml"
            '''
      }
    }
    stage('test') {
      steps {
        sh '''#!/bin/bash

echo "Test script"'''
      }
    }
    stage('deploy') {
      steps {
        sh '''#!/bin/bash

echo "deploy script"'''
      }
    }
  }
}
