#!/usr/bin/env bash
set -e

# this script must be called with root permissions
if [[ $(id -g loudly) != $2 || $(id -u loudly) != $1 ]]; then
    groupmod -g $2 loudly
    usermod -u $1 -g $2 loudly
fi;

cp /etc/profile /home/loudly/.profile
chown -R loudly:loudly /home/loudly
