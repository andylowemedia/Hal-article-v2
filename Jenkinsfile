pipeline {
  agent none
  environment {
    CI = 'true'
  }
  stages {
    stage('test') {
      agent { dockerfile true }
      steps {
        sh '''#!/bin/bash
            echo "Test script"
            cd appcode
            composer install
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

    stage('build') {
        agent any
        steps {
            echo "Build script"
            script {
                    def tag = sh(returnStdout: true, script: "git tag --contains | head -1").trim()

                    docker.build("low-emedia/hal-article:${tag}")
                    docker.withRegistry('https://540688370389.dkr.ecr.eu-west-1.amazonaws.com', 'ecr:eu-west-1:aws-lowemedia') {
                        docker.image("low-emedia/hal-article").push(tag)
                    }
            }
        }
    }
  }
}
