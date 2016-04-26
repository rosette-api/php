#!/bin/bash

retcode=0
ping_url="https://api.rosette.com/rest/v1"

#------------------ Functions ----------------------------------------------------

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

#Checks if Rosette API key is valid
function checkAPI {
    match=$(curl "${ping_url}/ping" -H "X-RosetteAPI-Key: ${API_KEY}" |  grep -o "forbidden")
    if [ ! -z $match ]; then
        echo -e "\nInvalid Rosette API Key"
        exit 1
    fi  
}

function cleanURL() {
    # strip the trailing slash off of the alt_url if necessary
    if [ ! -z "${ALT_URL}" ]; then
        case ${ALT_URL} in
            */) ALT_URL=${ALT_URL::-1}
                echo "Slash detected"
                ;;
        esac
        ping_url=${ALT_URL}
    fi
}

function validateURL() {
    match=$(curl "${ping_url}/ping" -H "X-RosetteAPI-Key: ${API_KEY}" |  grep -o "Rosette API")
    if [ "${match}" = "" ]; then
        echo -e "\n${ping_url} server not responding\n"
        exit 1
    fi  
}

function runExample() {
    echo -e "\n---------- ${1} start -------------"
    result=""
    if [ -z ${ALT_URL} ]; then
        result="$(php ${1} --key ${API_KEY} 2>&1 )"
    else
        result="$(php ${1} --key ${API_KEY} --url=${ALT_URL} 2>&1 )"
    fi
    echo "${result}"
    echo -e "\n---------- ${1} end -------------"
    if [[ "${result}" == *"Exception"* ]]; then
        echo "Exception found"
        retcode=1
    elif [[ "$result" == *"processingFailure"* ]]; then
        retcode=1
    elif [[ "$result" == *"AttributeError"* ]]; then
        retcode=1
    elif [[ "$result" == *"ImportError"* ]]; then
        retcode=1
    fi
}
#------------------ Functions End ------------------------------------------------

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

cleanURL

validateURL

#Copy the mounted content in /source to current WORKDIR
cp -r -n /source/* .
cp -r vendor /php-dev/examples

#Run the examples
if [ ! -z ${API_KEY} ]; then
    checkAPI
    cd /php-dev/examples
    if [ ! -z ${FILENAME} ]; then
        runExample ${FILENAME}
    else
        for file in *.php; do
            runExample ${file}
        done
    fi
else 
    HELP
fi


#Run unit tests
cd /php-dev && ./bin/phpspec run

#Run php-cs-fixer
./bin/php-cs-fixer fix . --dry-run --diff --level=psr2

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

exit ${retcode}
