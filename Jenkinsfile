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
            vendor/bin/phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude vendor .

            '''
        step([
            $class: 'CloverPublisher',
            cloverReportDir: 'appcode/public/coverage',
            cloverReportFileName: 'coverage.xml',
            healthyTarget: [methodCoverage: 100, conditionalCoverage: 100, statementCoverage: 100],
            unhealthyTarget: [methodCoverage: 100, conditionalCoverage: 100, statementCoverage: 100],
            failingTarget: [methodCoverage: 100, conditionalCoverage: 100, statementCoverage: 100]
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
