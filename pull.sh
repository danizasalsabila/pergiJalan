#!/bin/bash

git checkout main

git pull

docker compose rm -sf php && docker compose up -d php
