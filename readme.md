# project description
Specifications:
* PHP 8.1
* Symfony 6.3

Symfony skeleton is used. Few additional packages added:
* symfony/intl
* symfony/serializer-pack
* jms/serializer-bundle
* friendsofsymfony/rest-bundle
* api

## Data pipeline - filtering
The given data json resourse is minimised using python jupyter notebooks and Pandas. The raw data contains too mutch information that would be inefficient to read all the data on each request. Python Pandas is choosen because it is optimized for fast data filtering which is needed in this case.

## Data loading from json
For data loading from file strategy pattern is implemented in case there would be different file types. Data loading from database is not considered as there was no mention of databases in given task.

## Data processing
The RoutingService class is used for loading data and route search algorythm. Data loading could be flexible - by implementing strategy pattern. For route search Breadth First (BFS) algorythm is choosen.
