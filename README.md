#Wish List project
- Symfony 5.1
- PHP 7.4

### Prerequisites
To build project host machine must be installed Linux or MacOS and some required software:
latest version of `Docker` and `docker-compose`.
### Installation
Change variables in `.env` to set up project correctly

Default port mapping (see `.env` file):

        127.0.0.1:8080  - Applicaiton
        127.0.0.1:8081  - PHPMyAdmin (root/root)
        
To set up project:

        make setup
        
To run tests:

        make test     
        
To start environment:

        make start 
        
To stop environment:

        make stop     

### Utility Commands
 To generate CSV file with wish lists use:
 
        docker-compose exec app bin/console app:generate-csv-wish-list 
        
API documentation is available here [127.0.0.1:8080/docs](127.0.0.1:8080/docs) 

User Credentials:

        username: user
        password: password 