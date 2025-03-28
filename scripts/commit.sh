#!/bin/bash
export HOME=$1

set -e
git config --global init.defaultBranch master
git config --global user.name "$GIT_REPO_USERNAME"
git config --global user.email "$GIT_REPO_EMAIL"
git remote set-url origin "$GIT_REPO_URL"
git add -A
git commit -m "$2"
git push origin master