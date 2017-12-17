@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/phploc/phploc/composer/bin/phploc
php "%BIN_TARGET%" %*
