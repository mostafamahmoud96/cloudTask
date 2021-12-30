# Task Description

Laravel APIs to upload, delete and retrieve files

## Pre-required
Docker must be installed.

## Instructions
bash
git clone 'repo_url'
cp .env.example .env
sudo docker-compose up -d
sudo docker-compose exec app composer install
sudo docker-compose exec app php artisan migrate
sudo docker-compose exec app php artisan key:generate


## Usage
try APIs Postman.



##### to 'upload file'
[http://127.0.0.1:8000/api/upload/]()

##### to 'retrieve file'
[http://127.0.0.1:8000/api/getFileName?name={{name}}]()


##### to 'delete file'
[http://127.0.0.1:8000/api/deletefile/?name={{name}}]()
