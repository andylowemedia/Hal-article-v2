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
            cd appcode
            composer install
            '''
      }
    }
    stage('test') {
      steps {
        sh '''#!/bin/bash
            echo "Test script"
            cd appcode
            vendor/bin/phpunit

            vendor/bin/phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude vendor . || exit 0
            dry canRunOnFailed: true, pattern: "build/logs/pmd-cpd.xml"

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
    stage('deploy') {
      docker.build('latest');
    }
  }
}
