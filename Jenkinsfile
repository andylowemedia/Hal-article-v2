#!groovy
node {
        stage ('Building Environment') {
            try {
                checkout scm
                sh 'docker network create halv2_default'
                sh 'docker-compose build --no-cache && docker-compose up -d'
                sleep(180)
                sh 'docker exec hal-article-php composer development-enable'
            } catch (err) {
                sh 'docker-compose down -v'
                sh 'docker network rm halv2_default'
                throw err
            }

        }
        stage ('Testing: TDD Tests') {
            try {
                sh 'docker exec hal-article-php vendor/bin/phpunit'
                step([
                    $class: 'CloverPublisher',
                    cloverReportDir: 'appcode/public/coverage',
                    cloverReportFileName: 'coverage.xml',
                    healthyTarget: [methodCoverage: 80, conditionalCoverage: 80, statementCoverage: 80],
                    unhealthyTarget: [methodCoverage: 70, conditionalCoverage: 70, statementCoverage: 70],
                    failingTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50]
                ])
            } catch (err) {
                sh 'docker-compose down -v'
                sh 'docker network rm halv2_default'
                throw err
            }
        }
        stage ('Testing: BDD Tests') {
            try {
                sh 'docker exec hal-article-php vendor/bin/behat'
            } catch (err) {
                sh 'docker-compose down -v'
                sh 'docker network rm halv2_default'
                throw err
            }
        }
        stage ('Docker Cleanup') {
            sh 'docker-compose down -v'
            sh 'docker network rm halv2_default'
            sh 'rm -rf *'
        }
        stage ('Building & Push Docker Image') {
            checkout scm
            def tag = sh(returnStdout: true, script: "git tag --contains | head -1").trim()

            docker.build("low-emedia/hal-article:latest")
            docker.withRegistry('https://540688370389.dkr.ecr.eu-west-1.amazonaws.com', 'ecr:eu-west-1:aws-lowemedia') {
                docker.image("low-emedia/hal-article").push(tag)
            }
        }
        stage ('Deploying to ECS') {
            echo 'Deploying script TODO'
        }
}

properties properties: [
  disableConcurrentBuilds()
]