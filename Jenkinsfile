stage 'Hal Article Microservice'
node {
    stage ('Testing: Unit Tests') {
        try {
        checkout scm
        sh 'docker network create halv2_default'
        sh 'docker-compose build --no-cache && docker-compose up -d'
        sh 'docker ps'
        sh 'docker exec hal-article-php ls -lah'
        sh 'docker exec hal-article-php vendor/bin/phpunit'
        } catch (err) {
            sh 'docker-compose down -v'
            sh 'docker network rm halv2_default'
            throw err
        }
    }
}


// pipeline {
//   agent none
//   environment {
//     CI = 'true'
//   }
//   stages {
//     stage('test') {
//       agent { any }
//       steps {
//         echo env.BRANCH_NAME
//         echo env.TAG_NAME
//
//
//         sh '''#!/bin/bash
//             echo "Test script"
//             cd appcode
//             composer install
//             vendor/bin/phpunit
//             mkdir -p build/logs
//             vendor/bin/phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude vendor . || exit 0
//             '''
//         step([
//             $class: 'CloverPublisher',
//             cloverReportDir: 'appcode/public/coverage',
//             cloverReportFileName: 'coverage.xml',
//             healthyTarget: [methodCoverage: 80, conditionalCoverage: 80, statementCoverage: 80],
//             unhealthyTarget: [methodCoverage: 70, conditionalCoverage: 70, statementCoverage: 70],
//             failingTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50]
//         ])
//
//       }
//     }
//   }
// }
