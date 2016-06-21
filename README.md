API Framework
===========

This (non-restful) framework was created special for handling an API requests from any kind of client side applications.

Structure in shortly
-------
In this framework we have a dedicated objects. Every object have more that one action (method).<br />
Every signle action may accept POST or GET parameters. For example:<br />
##### books
* /books.get_available
* /books.like_book?book_id=1

Usage example
-------
Let's we have some mobile or desktop application that works with books and all of information stored on remote server.<br />
We put this API Framework on server and after that our application may work with it over HTTP.

1. Let's say that our app is needed to get list all of available books.To make this possible the client's app have to make a request:

```
http://api.<servername>/books.get_available
```

As result the app will receive the response of request. The response may have JSON, XML or usual text format. 
Say, we'd like to use JSON:

```json
{"status":"successful", "available_books":[
  {"id":1,"title":"Book's title","pages_count":374},
  {"id":2,"title":"Book's title 2","pages_count":965},
  {"id":3,"title":"Book's title 3","pages_count":145}
]}
```

2. What if we need to send or to update some information on server? To make this - we need to make a POST request like this one:

```php
http://api.<servername>/books.like_book
book_id: 1
```

As result may be marker that shows us result of request.

```json
{"status":"successful"}
```
