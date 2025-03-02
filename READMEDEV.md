this is a file with commands to help you out and use good practices
NOTE: Try to stay away from bin/console commands, instead try to use symfony console commands 
symfony serve

if you have a error when first starting its usually the command "composer install"

some usefull DB commands:
composer require debug  
composer require doctrine  
docker compose up -d   
symfony var:export --multiline  --> shows your env data, you dont REALLY need your env. symfony does this for you. 

symfony packs that have been previously installed already setup the link for you. 


symfony console doctrine:schema:validate


symfony console make migration
symfony console doctrine:migrations:list

ymfony console cache:clear 


to make a migration, here are the full commands. 
- symfony console make:entity 
- symfony console make:migration
- symfony console doctrine:migrations:list --> its to check your migrations set at the moment. 
- symfony console doctrine:migrations:migrate -- note: it might try to migrate a migration youve made earlier, just move into a diffrent folder, i used a selfmade folder /migrations/finishedMigration/Yourmigration.php

