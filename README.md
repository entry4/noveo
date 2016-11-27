Noveo PHP developer test task
============================
REQUIREMENTS
------------

The minimum requirement by this project that your Web server runs Apache2 and supports PHP 5.6.0.
To use Nginx you have to configure it to support `prettyUrl` functionality (please follow [this guide](http://www.yiiframework.com/doc-2.0/guide-start-installation.html))

The project was tested on Apache2 + php7.0

INSTALLATION
------------
Clone this repository

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project using the following commands:

~~~
php composer.phar global require "fxp/composer-asset-plugin:^1.2.0"
php composer.phar update
php composer.phar install
~~~

Now you should configure db connection in `config/db.php`. The only requirement here is to left `dbname=noveo` in your dsn string

Next step is to import schemas structure from `models/dbDump/full.sql` to your MySQL db.
You may just use your favorite GUI or run 
~~~
mysql -uUSERNAME -pUSERPASSWORD < /path/to/project/models/dbDump/full.sql
~~~

Now you should be able to access the API through the following URLs, assuming `noveo` is the directory
directly under the Web root.

~~~
http://localhost/noveo/web/users
http://localhost/noveo/web/groups
~~~

API Request examples
-------

Fetch list of users
```
GET /noveo/web/users HTTP/1.1
HOST: localhost
content-type: application/json
```
Fetch user info
```
GET /noveo/web/users/5 HTTP/1.1
HOST: localhost
content-type: application/json

```

Create a new user
 
`email` should be unique valid email adress

`state` is unnecessary and takes `1` by default

`creationDate` is unnecessary and takes current dateTime by default
```
POST /noveo/web/users HTTP/1.1
HOST: localhost
content-type: application/json
content-length: 138

{
  "email":"username@server.com",
  "firstName":"name",
  "lastName":"lastname",
  "state":1,
  "groupId":null,
  "creationDate":"2016-01-01 00:00:00"
}
```


Change user's info
```
PUT /noveo/web/users/5 HTTP/1.1
HOST: localhost
content-type: application/json
content-length: 138

{
  "firstName":"name",
  "lastName":"lastname",
  "groupId":9
}
```

Delete user
```
DELETE /noveo/web/users/5 HTTP/1.1
HOST: localhost
content-type: application/json
content-length: 2

```

Fetch list of groups

```
GET /noveo/web/groups HTTP/1.1
HOST: localhost
content-type: application/json
```

Create a group
```
POST /noveo/web/groups HTTP/1.1
HOST: localhost
content-type: application/json
content-length: 26

{
"name":"testGroup"
}
```
Modify group info
```
PUT /noveo/web/groups/12 HTTP/1.1
HOST: localhost
content-type: application/json
content-length: 29

{
"name":"testGroup123"
}
```
Delete group

`NOTE` that group can not be deleted while it's linked to some user 
```
DELETE /noveo/web/groups/12 HTTP/1.1
HOST: localhost
content-type: application/json
content-length: 2
```

TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).

Please check `tests/functional.suite.yml` params before running tests

Tests can be executed by running

```
composer exec codecept run
``` 

The command above will execute functional tests