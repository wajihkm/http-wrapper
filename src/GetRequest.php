<?php

namespace Directions\HttpWrapper;

use Directions\HttpWrapper\Enums\BodyType;
use Directions\HttpWrapper\Enums\HttpType;
use Directions\HttpWrapper\Enums\ResponseType;
use Directions\HttpWrapper\Traits\QueryParams;

abstract class GetRequest extends Request
{
    // Add if the request uses Query params
    // This will add the @setQueryParams(array $params)
    // and then will auto add these params
    use QueryParams;

    protected HttpType $http_type = HttpType::GET;

    protected BodyType $body_type = BodyType::Form;

    protected ResponseType $response_type = ResponseType::Json;
}
