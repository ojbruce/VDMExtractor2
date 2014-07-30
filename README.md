The VDM Extractor2
===================

** The second edition of vdm extractor **

* Goal : use amazing framework

Requirements
------------
    Smile

Extractor
---------

- Generate database entities :

``` app/console doctrine:generate:entities 
VDMExtractor/ExtractorBundle/Entity/Post```


- Create database schema :

```app/console doctrine:schema:create```




- Store 200 VDM posts in your database :

``` app/console extract:vdm 200 ```





Powered by
----------

PHP,Symfony 2 & Doctrine ORM.