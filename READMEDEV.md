this is a file with commands to help you out and use good practices
NOTE: Try to stay away from bin/console commands, instead try to use symfony console commands 
symfony serve

if you have a error when first starting its usually the command "composer install"

some usefull DB commands:
composer require debug  
composer require doctrine  
docker compose up -d   
symfony var:export --multiline  

symfony packs that have been previously installed already setup the link for you. 


symfony console doctrine:schema:validate


symfony console make migration
symfony console doctrine:migrations:list