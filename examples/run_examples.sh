#!/bin/bash

OPTS=""
if [ -n "$2" ]; then
    OPTS="--url=$2"
fi

for f in *.php
do
    php $f --key $1 "$OPTS"
done