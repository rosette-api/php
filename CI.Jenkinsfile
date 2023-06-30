// These are Debian images.
//def php_versions = [7.1, 7.2, 7.3, 7.4, 8.0, 8.1, 8.2]
def php_versions = [7.4]

def runVersion(sourceDir, ver) {
    mySonarOpts = "-Dsonar.sources=source -Dsonar.host.url=${env.SONAR_HOST_URL} -Dsonar.login=${env.SONAR_AUTH_TOKEN}"
    if ("${env.CHANGE_ID}" != "null") {
        mySonarOpts = "$mySonarOpts -Dsonar.pullrequest.key=${env.CHANGE_ID} -Dsonar.pullrequest.branch=${env.BRANCH_NAME}"
    } else {
        mySonarOpts = "$mySonarOpts -Dsonar.branch.name=${env.BRANCH_NAME}"
    }
    if ("${env.CHANGE_BRANCH}" != "null") {
        mySonarOpts = "$mySonarOpts -Dsonar.pullrequest.base=${env.CHANGE_TARGET} -Dsonar.pullrequest.branch=${env.CHANGE_BRANCH}"
    }

    // Only run Sonar once.  Use 7.4 until we get our 8.x ducks in a row.
    if (ver == 7.4) {
        sonarExec = "cd /root/ && \
               wget -q https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-4.8.0.2856-linux.zip && \
               unzip -q sonar-scanner-cli-4.8.0.2856-linux.zip && \
               cd /php-source && \
               /root/sonar-scanner-4.8.0.2856-linux/bin/sonar-scanner ${mySonarOpts}"
    } else {
        sonarExec = "echo Skipping Sonar for this version."
    }

    echo "Testing PHP version ${ver}"
    sh "docker run --rm \
            --pull always \
            -e ROSETTE_API_KEY=${env.ROSETTE_API_KEY} \
            -v ${sourceDir}:/php-source \
            php:${ver}-cli \
            bash -c \"cd /php-source && \
                      ./CI.sh && \
                      ${sonarExec}\""
}

node ("docker-light") {
    def sourceDir = pwd()
    try {
        stage("Clean up") {
            step([$class: 'WsCleanup'])
        }
        stage("Checkout Code") {
            checkout scm
        }
        stage("Build and Test") {
            withSonarQubeEnv {
                php_versions.each { ver ->
                    runVersion(sourceDir, ver)
                }
            }
        }
        postToTeams(true)
    } catch (e) {
        currentBuild.result = "FAILED"
        postToTeams(false)
        throw e
    }
}

def postToTeams(boolean success) {
    def webhookUrl = "${env.TEAMS_PNC_JENKINS_WEBHOOK_URL}"
    def color = success ? "#00FF00" : "#FF0000"
    def status = success ? "SUCCESSFUL" : "FAILED"
    def message = "*" + status + ":* '${env.JOB_NAME}' - [${env.BUILD_NUMBER}] - ${env.BUILD_URL}"
    office365ConnectorSend(webhookUrl: webhookUrl, color: color, message: message, status: status)
}
