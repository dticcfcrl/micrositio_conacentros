#!/bin/bash
git config --global init.defaultBranch master
git init
git remote add origin "$GIT_REPO_URL"
git fetch origin
git reset --hard origin/master
git config --global user.name "$GIT_REPO_USERNAME"
git config --global user.email "$GIT_REPO_EMAIL"