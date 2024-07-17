def network_name = "motorspeed-prod-network"
def volume_path = "/home/dockers/motorspeed_laravel11_prod"
def container_name = "motorspeed-laravel11-prod"
def mariadb_hostname = "motorspeed-mariadb-prod"
def mariadb_user = "motorspeed_dba"
def mariadb_password = "1234"
def mariadb_database = "motorspeed_site"
def laravel_port = "9012"

pipeline { 
  agent none
  stages {
    stage('Remove container...') {
      agent any
      steps {
        script {
          try {
            sh "docker rm -f ${container_name}"
          } catch(e) {
            echo e
          }
        }
      }
    }
    stage('Build') {
      agent any
      steps {
        script {
          try {
            sh "docker run -d --name ${container_name} \
            -p ${laravel_port}:8000 \
            --env DB_HOST=${mariadb_hostname} \
            --env DB_PORT=3306 \
            --env DB_DATABASE=${mariadb_database} \
            --env DB_USERNAME=${mariadb_user} \
            --env DB_PASSWORD=${mariadb_password} \
            --network ${network_name} \
            --volume ${volume_path}:/app \
            --restart unless-stopped \
            bitnami/laravel:latest"

            sh "cp .env.prod .env"

            echo "Container ${container_name} done!"
          } catch(e){
            echo e
          }
        }
      }
    }
  }
}