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
            mkdir -p build/logs
            vendor/bin/phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude vendor . || exit 0
            '''
        step([
            $class: 'CloverPublisher',
            cloverReportDir: 'appcode/public/coverage',
            cloverReportFileName: 'coverage.xml',
            healthyTarget: [methodCoverage: 80, conditionalCoverage: 80, statementCoverage: 80],
            unhealthyTarget: [methodCoverage: 70, conditionalCoverage: 70, statementCoverage: 70],
            failingTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50]
        ])

      }
    }

    stage('build') {
        agent any
        steps {
            echo "Build script"
            script {
                    def tag = sh(returnStdout: true, script: "git tag --contains | head -1").trim()

                    docker.build("low-emedia/hal-article:latest")
                    docker.withRegistry('https://540688370389.dkr.ecr.eu-west-1.amazonaws.com', 'ecr:eu-west-1:aws-lowemedia') {
                        docker.image("low-emedia/hal-article").push(tag)
                    }
            }
        }
    }
  }
}
