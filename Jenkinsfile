pipeline {
  agent {
    docker {
        image 'php:latest'
        args '-p 3000:3000'
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
            pwd
            ls -lah
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
