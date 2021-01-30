# file-manager

To run this app there are two ways: using docker-compose or using php server. I pushed the ```vendor``` files to save time installing and generating autoload files but it is recommeded that you run ```composer install``` and ```composer dump -o```.

## To use docker-compose

Start by modifying the ```src/Connection/Connection.php``` file, replacing the db_host with your machine's IP address. Once that is complete run ```docker-compose build``` and ```docker-compose up```. Everything will be generated automatically including the database tables and will be available at port ```8002```

## To use php server

In the sql directory there is an sql script. Using command line or sql management app create the database and import the sql script.
Make sure you change the variables in the ```src/Connection/Connection.php``` to fit your configuration. 
The app should be ready to work now. From the root directory or preferably the public directory, run ``` php -S localhost:8000``` or any other port.
