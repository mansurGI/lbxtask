# Local Brand X test task

Test task by Mansur Gainetdinov

# Run

Requirements: bash, docker, docker compose plugin, curl

There is 2 ways to use this project. First - run it on local machine. Second - use it from my personal server (project
will be available on my server for a few weeks). So you can skip this part and go straight to "Use" section with my
server IP = 185.43.6.137

Or execute this command to start a project.

```
bash install.bash
```

It will create a docker image for FPM container, run containers (fpm, nginx, rabbit, mariadb),
install composer requirements, create databases, make migrations and start message consumer.

# Use

Execute this command to import a csv file

```
curl -X POST -H 'Content-Type: text/csv' --data-binary @import.csv 127.0.0.1:180/api/employee > curl.html
```

Request the data of Employee #198429 (the first in a row)

```
GET 127.0.0.1:180/api/employee/198429
```

You can also import another csv with additional and duplicated data

```
curl -X POST -H 'Content-Type: text/csv' --data-binary @import2.csv 127.0.0.1:180/api/employee > curl.html
```

Request a new Employee #777777 from import2.csv

```
GET 127.0.0.1:180/api/employee/777777
```

# A little about project

Import route gets a request content as stream. Saves it into our selected storage. Passes a message with name of file to
queue. The handler gets a file from storage, passes it to csv parsing library and reads row by row. Each row is
serialized, validated and INSERT IGNORE in database inside a 20 statements transaction.

The code is ready to add new .csv files types (more or less columns), to add new storages and connections.

I have created a database table for Employee with unique index on Employee ID (we can also create an index for username
and email columns). For deletion of Employee I have added a status column. For size reduction middleInitial is char(1),
phone is char(12).

An IndexController::index() route=/ will execute a bin/test.php (migrations, fixtures and run all tests) and show it's
output - this is just a trick for easier development on my server. So you can use to see test results without going into
container.

The FPM config disables opcache for easier development.

For batch insert I have created a new Connection with an 'INSERT IGNORE' statement (so all unique violations go as
warnings). Every batch is 20 statements. We also have a MAX_ERRORS constant (20 errors max) for predictability of time
costs. So we know that a broken file will not kill our database, our consumer and will not take infinit amount of time.

There is some Unit tests for things that can be tested without Kernel. There is no Application level tests, because all
is already tested at Integration level. Some things just don't need to be tested (like a LocalStorage), because there is
no functionality to test.

There is a CsvFieldsSet. It gets a header of a .csv file and gives a corrected version. It exists to secure column
position changes, name changes (it has alias for Employee fields)

There is only one way to message be handled again (3 retries with 1 sec delay) - Doctrine Connection Exception (database
is out of service). Any other errors will write to log, because there is no automated way to handle wrong files. (Even
exceeding the MAX_ERRORS constant will stop handling message).

The problem with scaling might be just a MySQL (max connections, maximum throughput)

# What to improve

- We can add permission to view, delete and import Employees
- We can prepare this project to production environment
- We can create a Symfony Command that will be run by cron to clean the imported.csv files
- Create a consumer container for consuming messages for scalability
- We can add a validation for .csv file
- We can add Locks to file processing
- We can send failed .csv files to 'error/failed' queue and somehow handle them (for ex. store in today's broken .csv
  for human inspection)
- Add a UNICODE support (there is no trim() for unicode in php, we can install Symfony String Component)

# What can be done differently

- Testing: We should create a trait/phpunit extension for executing tests that works with database (clean, fixtures
  before each test)
- Testings: test executing flow = unit -> integration -> application

# Stop and Uninstall

Execute this lines to stop all containers and delete images, then delete lbx-test-task folder
(!LOOK AT CODE BEFORE EXECUTING!)

```
docker compose down

docker rmi bitnami/nginx php-fpm-custom mariadb bitnami/rabbitmq
```