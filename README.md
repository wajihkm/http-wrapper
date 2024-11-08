# Http Facade Wrapper
Wrapper around HTTP Facade in Laravel for simple requests

Converts any request into Entity/Class.

## Installation & Usage

> **Requires [PHP 8.1+](https://php.net/releases/)**

Require HttpWrapper using [Composer](https://getcomposer.org):

```bash
composer require directions/http-wrapper
```

## How to use?

### Simple HTTP request:

```
<?php

use Directions\HttpWrapper\Request;

use Directions\HttpWrapper\Traits\QueryParams;
use Directions\HttpWrapper\Traits\UserAgent;

class MyRequest extends Request
{
    // Add if the request uses Query params
    // This will add the @setQueryParams(array $params)
    // and then will auto add these params
    use QueryParams;

    // Add if the request should use custom user-agent value
    // setUserAgent(string $user_agent)
    use UserAgent;

    protected int $timeout = 30;

    protected string $base_url;

    protected string $path;

    protected HttpType $http_type;

    protected BodyType $body_type;

    protected ResponseType $response_type = ResponseType::None;
}
```

Make sure to init your values.

Then call:

```
MyRequest::getInstance()->submit();
```

### Add Payload:

```
class MyRequest extends Request
{
    public function getPayload(): array
    {
        return [
            'param_1' => 1,
            'param_2' => 'value 2',
        ];
    }
}
```

### Parse the response:
```
class MyRequest extends Request
{
    public function parse_response(Response $response): mixed
    {
        if ($response->successful()) {
            return $response->object();
        }

        return null;
    }
}

MyRequest::getInstance()->get_response();
```
