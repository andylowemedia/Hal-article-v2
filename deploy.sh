#!/usr/bin/env bash

echo "Registering Task Definitions:"
TaskRevisionString=$(aws ecs register-task-definition --region eu-west-1 --cli-input-json file://task-definitions.json | grep '"revision": ')
TaskRevision=$(echo $TaskRevisionString | sed -e "s/^\"revision\": //g" -e "s/,$//g")

echo "Deploying containers using task definition"
aws ecs update-service --cluster Hal-microservices --service hal-article-v2 --task-definition hal-article:$TaskRevision