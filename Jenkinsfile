#!groovy
properties([[$class: 'JiraProjectProperty'], disableConcurrentBuilds(), pipelineTriggers([[$class: 'PeriodicFolderTrigger', interval: '10m']])])
node {
        stage ('Building Environment') {
            try {
                checkout scm
                sh "sed -i \"s/#{TAG_NAME}#/${env.TAG_NAME}/\" docker-compose.jenkins.yml"
                sh "sed -i \"s/#{BUILD_NAME}#/${currentBuild.number}/\" docker-compose.jenkins.yml"

                sh "docker network create halv2_default-${env.TAG_NAME}-build-${currentBuild.number}"
                sh 'docker-compose -f docker-compose.jenkins.yml build --no-cache && docker-compose -f docker-compose.jenkins.yml up -d'
                sleep(300)
                sh "docker exec hal-article-php-${env.TAG_NAME}-build-${currentBuild.number} composer development-enable"
            } catch (err) {
                sh 'docker-compose -f docker-compose.jenkins.yml down -v'
                sh "docker network rm halv2_default-${env.TAG_NAME}-build-${currentBuild.number}"
                sh "rm -rf * && rm -rf .*"
                throw err
            }

        }
        stage ('Testing: TDD Tests') {
            try {
                sh "docker exec hal-article-php-${env.TAG_NAME}-build-${currentBuild.number} vendor/bin/phpunit"
                step([
                    $class: 'CloverPublisher',
                    cloverReportDir: 'appcode/public/coverage',
                    cloverReportFileName: 'coverage.xml',
                    healthyTarget: [methodCoverage: 80, conditionalCoverage: 80, statementCoverage: 80],
                    unhealthyTarget: [methodCoverage: 70, conditionalCoverage: 70, statementCoverage: 70],
                    failingTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50]
                ])
            } catch (err) {
                sh 'docker-compose -f docker-compose.jenkins.yml down -v'
                sh "docker network rm halv2_default-${env.TAG_NAME}-build-${currentBuild.number}"
                sh "rm -rf * && rm -rf .*"
                throw err
            }
        }
        stage ('Testing: BDD Tests') {
            try {
                sh "docker exec hal-article-php-${env.TAG_NAME}-build-${currentBuild.number} vendor/bin/behat"
            } catch (err) {
                sh 'docker-compose -f docker-compose.jenkins.yml down -v'
                sh "docker network rm halv2_default-${env.TAG_NAME}-build-${currentBuild.number}"
                sh "rm -rf * && rm -rf .*"
                throw err
            }
        }
        stage ('Docker Cleanup') {
            sh 'docker-compose -f docker-compose.jenkins.yml down -v'
            sh "docker network rm halv2_default-${env.TAG_NAME}-build-${currentBuild.number}"
            sh "rm -rf * && rm -rf .*"
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
