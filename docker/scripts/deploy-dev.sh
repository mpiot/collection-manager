#!/bin/bash

docker tag $APP_DEV_IMAGE_NAME:$APP_DEV_IMAGE_TAG $APP_DEV_IMAGE_NAME:latest

docker push $APP_DEV_IMAGE_NAME:$APP_DEV_IMAGE_TAG
docker push $APP_DEV_IMAGE_NAME:latest
