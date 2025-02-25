@echo off
REM Delete all local tags
for /f "delims=" %%a in ('git tag -l') do git tag -d %%a

REM Fetch all tags from the remote repository
git fetch --tags
