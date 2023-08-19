# project description
Symfony skeleton is used. With few additional packages.
Packages installed:
* composer require symfony/intl
* composer require symfony/serializer-pack
* composer require jms/serializer-bundle
* composer require friendsofsymfony/rest-bundle
* composer require api

## Data pipeline - filtering
The given data json resourse is minimised using python jupyter notebooks and Pandas. The raw data contains too mutch information that would be inefficient to read all the data on each request. Python Pandas is choosen because it is optimized for fast data filtering which is needed in this case.

## Data processing
The RoutingService class is used for loading data and route search algorythm. Data loading could be flexible - by implementing strategy pattern. For route search Breadth First (BFS) algorythm is choosen.
