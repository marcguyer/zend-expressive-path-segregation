# Expressive Skeleton with Path-Segregated Modules

This is a test implementation of the concept of path-segregated modules documented at https://docs.zendframework.com/zend-expressive-hal/cookbook/path-segregated-uri-generation/

When using path-segregated middleware, the path used to segregate is _not_ available to the middleware. Since in this case, we need to generate links (and potentially for other reasons), we need to inform various components which path to add back in when generating URIs based on routes. For example, the `UrlHelper` needs to know how to generate links properly with the segregated path included in the links.

This proof-of-concept includes three modules: `Api`, `Auth`, and `Admin`. The names are somewhat meaningless. We just need three separate modules to demonstrate these techniques. The first two, `Api` and `Auth`, are path segregated by `/api` and `/auth` paths respectively. The third, `Admin` is also segregated by path (`/admin`) but also uses the Zend Stratigility function `host()` to pipe a custom middleware which has the only function of replacing a subdomain with a base path. The `Admin` module is then segregated by that path.

## Try it Out

Clone this repo, then...

```sh
$ composer run --timeout=0 serve
```

For the subdomain `host()` and `path()` segregation technique, you'll need a hostname with subdomain pointing to your built-in webserver IP. For example, add this to your `/etc/hosts` file:

```
0.0.0.0 admin.example.com
```

Then test the routes for each module:

```sh
$ curl http://0.0.0.0:8080/api/ping
{
    "ack": 1540566648,
    "_links": {
        "self": {
            "href": "http://0.0.0.0:8080/api/ping"
        }
    }
}
```

```sh
$ curl http://0.0.0.0:8080/auth/ping
{
    "ack": 1540566648,
    "_links": {
        "self": {
            "href": "http://0.0.0.0:8080/auth/ping"
        }
    }
}
```

```sh
$ curl http://admin.example.com:8080/ping
{
    "ack": 1540566648,
    "_links": {
        "self": {
            "href": "http://admin.example.com:8080/ping"
        }
    }
}
```

## Problem Solved

That's probably not very exciting. Here's the gist of it: If we hadn't created an unique instance of the UrlHelper for our isolated module (and all other dependent components), the result would instead be something like the following. Note that the link in the HAL document does not contain the segregated path and so would result in a 404 if followed:

```sh
$ curl http://0.0.0.0:8080/api/ping
{
    "ack": 1540566648,
    "_links": {
        "self": {
            "href": "http://0.0.0.0:8080/ping"
        }
    }
}
```

### Path-Segregated by Hostname

For the `Admin` module, we use a custom middleware to add a base path to the URI based on the subdomain value. Coincidentally, this eliminates the need to isolate all of the service names like what must be done to make path segregation with HAL work like in the `Api` and `Auth` modules.
