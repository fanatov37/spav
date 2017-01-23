# SPAV (Stored Programs and views) library.


#### About library and Recommendation:
I wrote this library as wrapper for use Procedure, Function or View for MySql.

My recommendation is use business logic of data base in Stored Programs. Your **MySql user** must have privileges only for Stored Programs and Views. **Full privileges** is available only for **root** user. 

[MySql Privileges](https://dev.mysql.com/doc/refman/5.7/en/privileges-provided.html)



#### Tips and Tricks:
- Currently this library available for **zend framework 2 or 3** with **MySql**
- You must use **php7**
- You should use **MySql5.7** because Stored Programs must return result as **json** (5.7 version available for json function). But you can use previous versions of MySql, just return string as json(use **concat** function for it).

####  Using:
Check out PHPUnit test(folder **tests**). You can find example of use Stored Programs (**/tests/EntityExample/**) or check out this repository  [Spav Zend Framework example](https://github.com/fanatov37/zf3) for example.