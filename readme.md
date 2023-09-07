# Project description
## The task
Create a simple service in PHP using the framework of your choice (Symfony is preferred), that is able to calculate any possible land route from one country to another. The objective is to take a list of country data in JSON format and calculate the route by utilizing individual countries' border information. 
The given world countries json file with borders. The app should find the first possible route and return array of countries as a route.
PHP using framework of your choice (Symfony is preferred)
* Data link: https://raw.githubusercontent.com/mledoze/countries/master/countries.json
* The application exposes REST endpoint /routing/{origin}/{destination} that returns a list of border crossings to get from origin to destination 
* Single route is returned if the journey is possible 
* Algorithm needs to be efficient 
* If there is no land crossing, the endpoint returns HTTP 400 
* Countries are identified by cca3 field in country data 
* HTTP request sample (land route from Czech Republic to Italy): 
* * GET /routing/CZE/ITA HTTP/1.0 : 
{ 
"route": ["CZE", "AUT", "ITA"] 
}
* source code should be covered with tests.

## Tech specifications:
* PHP 8.1
* Symfony 6.3

Symfony skeleton is used. Few additional packages added:
* symfony/intl
* symfony/serializer-pack
* jms/serializer-bundle
* friendsofsymfony/rest-bundle
* api

# Solution
## Data pipeline - filtering
* Countries data: As the borders of countries and countries does not change rapidly - the solution is to hold filtered data inside application.
The given data json resourse is minimised using Python Jupyter notebooks and Pandas. The raw data contains too mutch information that would be inefficient to read all the data on each request. Python Pandas is choosen because it is optimized for fast data filtering which is needed in this case.
In real life application some kind of cache server or database could be used instead.

## Data loading from json
For data loading from file strategy pattern is implemented in case there would be different file types. Data loading from database is not considered as there was no mention of databases in given task.

## Data processing
The RoutingService class is used for loading data and route search algorythm. Data loading could be flexible - by implementing strategy pattern. For route search Breadth First (BFS) algorythm is choosen.

# How to run
The project is not dockerized as there was no requirement in the task. The app can be run by using terminal command `php -S 127.0.0.1 -t public/` to acceess `http://127.0.0.1/routing/{origin}/{destination}` endoint.
