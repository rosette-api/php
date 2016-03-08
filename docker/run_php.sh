#!/bin/bash

#Gets called when the user doesn't provide any args
function HELP {
    echo -e "\nusage: source_file.php --key API_KEY [--url ALT_URL]"
    echo "  API_KEY       - Rosette API key (required)"
    echo "  FILENAME      - PHP source file (optional)"
    echo "  ALT_URL       - Alternate service URL (optional)"
    echo "  GIT_USERNAME  - Git username where you would like to push regenerated gh-pages (optional)"
    echo "  VERSION       - Build version (optional)"
    echo "Compiles and runs the source file(s) using the local development source."
    exit 1
}

#Gets API_KEY, FILENAME, ALT_URL, GIT_USERNAME and VERSION if present
while getopts ":API_KEY:FILENAME:ALT_URL" arg; do
    case "${arg}" in
        API_KEY)
            API_KEY=${OPTARG}
            usage
            ;;
        ALT_URL)
            ALT_URL=${OPTARG}
            usage
            ;;
        FILENAME)
            FILENAME=${OPTARG}
            usage
            ;;
        GIT_USERNAME)
            GIT_USERNAME=${OPTARG}
            usage
            ;;
        VERSION)
            VERSION={OPTARG}
            usage
            ;;
    esac
done

#Checks if Rosette API key is valid
function checkAPI {
    match=$(curl "https://api.rosette.com/rest/v1/ping" -H "user_key: ${API_KEY}" |  grep -o "forbidden")
    if [ ! -z $match ]; then
        echo -e "\nInvalid Rosette API Key"
        exit 1
    fi  
}

#Copy the mounted content in /source to current WORKDIR
cp -r -n /source/* .
cp -r vendor /php-dev/examples

#Run the examples
if [ ! -z ${API_KEY} ]; then
    checkAPI
    cd /php-dev/examples
    if [ ! -z ${FILENAME} ]; then
        if [ ! -z ${ALT_URL} ]; then
            php ${FILENAME} --key ${API_KEY} --url=${ALT_URL} 
        else
        php ${FILENAME} --key ${API_KEY} 
        fi
    elif [ ! -z ${ALT_URL} ]; then
            find -maxdepth 1  -name '*.php' -print -exec php {} --key ${API_KEY} --url=${ALT_URL} \;
    else
        find -maxdepth 1  -name '*.php' -print -exec php {} --key ${API_KEY} \;
    fi
else 
    HELP
fi


#Run unit tests
cd /php-dev && ./vendor/bin/phpunit -v --bootstrap ./vendor/autoload.php ./tests/rosette/api/ApiTest.php

#Run php-cs-fixer
./vendor/bin/php-cs-fixer fix . --dry-run --diff

#Generate gh-pages and push them to git account (if git username is provided)
if [ ! -z ${GIT_USERNAME} ] && [ ! -z ${VERSION} ]; then
    #clone php git repo to the root dir
    cd /
    git clone git@github.com:${GIT_USERNAME}/php.git
    cd php
    git checkout origin/gh-pages -b gh-pages
    git branch -d develop
    #generate gh-pages from development source and output the contents to php repo
    cd /php-dev
    ./vendor/bin/phpdoc -d ./source/rosette/api -t /php
    cd /php
    find -name 'phpdoc-cache-*' -exec rm -rf {} \;
    git add .
    git commit -a -m "publish php apidocs ${VERSION}"
    git push
fi