API Framework
===========

This framework was created special for making an API for service or website.

Structure in shortly
-------
In this framework we have an dedicated objects. Every of object have actions.<br />
Every of action may accept POST or GET parameters. For example:<br />
##### books
* get_available
* like_book?book_id=1

Usage example
-------
Let's we have some mobile or desktop application that works with books and all of information stored on remote server.<br />
We put this API Framework on server, and after that our application may work with it over Internet.

1. Let's our app is need to get list all of available books.<br />
>To make this, the app have to send request
>```
>http://api.<servername>/books.get_available
>```
As result the app will receive the response of request. The response may have JSON, XML or usual text format. 
Say, we like to use JSON.
```json
{"available_books":[
  {"id":1,"title":"Book's title","pages_count":374},
  {"id":2,"title":"Book's title 2","pages_count":965},
  {"id":3,"title":"Book's title 3","pages_count":145}
]}
```

2. What if we need to send some information to server?<br />
> To make this we have two ways of sending data. There are GET & POST. <br /> <br />
If we want to use GET, we should do this:
```
>http://api.<servername>/books.like_book?&book_id=1
>```
If we want to use POST, we should do this:
```php
http://api.<servername>/books.like_book
book_id: 1
>```
As result may be marker that shows us result of request.
```json
{"status":"successful"}
>```
