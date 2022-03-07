# news-api-test-task
Symfony API implementation
## Deploy information
git clone git@github.com:etilite/docker-symfony-template.git

cd docker-symfony-template

mv app/.env.local .

git clone git@github.com:etilite/news-api-test-task.git app/

mv .env.local app/

sudo docker-compose up -d

sudo docker-compose exec php composer install

sudo docker-compose exec php symfony console doctrine:migrations:migrate -n

Thats it! Now you can open http://your-docker-host:8080/news

