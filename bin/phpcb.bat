@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/mayflower/php-codebrowser/bin/phpcb
php "%BIN_TARGET%" %*
