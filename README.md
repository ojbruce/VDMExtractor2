The VDM Extractor2
===================

** The second edition of vdm extractor **

* Goal : use amazing framework

Requirements
------------
* Smile

Extractor
---------

- Generate database entities :

``` app/console doctrine:generate:entities 
VDMExtractor/ExtractorBundle/Entity/Post```


- Create database schema :

```app/console doctrine:schema:create```




- Store 200 VDM posts in your database :

``` app/console extract:vdm 200 ```


API
---

The API uses HTML methods to retrieve stored posts.
It returns Json.

To launch don't forget to start the server if not done:
```app/console server:run ```


**Get every posts**

``` http://host:port/api/posts  ```

**Filter posts**

*By author...*

``` http://host:port/api/posts?author=Michel ```

*By from date...*

``` http://host:port/api/posts?from=2014-07-01 ```

*By from & to date...*

``` http://host:port/api/posts?from=2014-07-01&to=2014-07-15 ```

**Get a single post by its id**

``` http://host:port/api/posts/post_id ```


Tests
------

Launch every test of the bundle

``` phpunit -c app src/VDMExtractor/ExtractorBundle/ ```

``` phpunit -c app src/VDMExtractor/APIBundle/ ```



Powered by
----------

PHP,Symfony 2 & Doctrine ORM.