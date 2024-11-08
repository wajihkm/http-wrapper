<?php

namespace Directions\HttpWrapper;

use Directions\HttpWrapper\Enums\BodyType;
use Directions\HttpWrapper\Enums\HttpType;
use Directions\HttpWrapper\Enums\ResponseType;

abstract class PostRequest extends Request
{
    protected HttpType $http_type = HttpType::POST;

    protected BodyType $body_type = BodyType::Json;

    protected ResponseType $response_type = ResponseType::Json;
}
