pipeline {
  agent {
    docker {
        image '540688370389.dkr.ecr.eu-west-1.amazonaws.com/low-emedia/php:latest'
        args '-p 9000:9000  -v /var/coverage/reports:/var/www/html/public/coverage -td'
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
            cd appcode
            composer install
            vendor/bin/phpunit
            '''
        step([
            $class: 'CloverPublisher',
            cloverReportDir: 'appcode/public/coverage',
            cloverReportFileName: 'coverage.xml',
            healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80], // optional, default is: method=70, conditional=80, statement=80
            unhealthyTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50], // optional, default is none
            failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]     // optional, default is none
        ])
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
