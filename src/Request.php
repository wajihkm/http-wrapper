<?php

namespace Directions\HttpWrapper;

use Directions\HttpWrapper\Enums\BodyType;
use Directions\HttpWrapper\Enums\HttpType;
use Directions\HttpWrapper\Enums\ResponseType;
use Directions\HttpWrapper\Traits\QueryParams;
use Directions\HttpWrapper\Traits\UserAgent;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class Request
{
    protected int $timeout = 30;

    protected string $base_url;

    protected string $path;

    protected HttpType $http_type;

    protected BodyType $body_type;

    protected ResponseType $response_type = ResponseType::None;

    private ?string $bearer_token = null;

    //

    private static $instances = [];

    public static function getInstance(?string $bearer_token = null)
    {
        $subclass = static::class;
        if (! isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static($bearer_token);
        }

        return self::$instances[$subclass];
    }

    //

    public function __construct(?string $bearer_token = null)
    {
        $this->setBaseUrl();
        $this->setPathUrl();

        if ($bearer_token) {
            $this->setToken($bearer_token);
        }
    }

    protected function setBaseUrl(): void {}

    protected function setPathUrl(): void {}

    private function _url_build(bool $remove_trailing_slash = false): string
    {
        $base = rtrim($this->base_url, '/');

        $path = ltrim($this->path ?? '', '/');

        $url = $base.'/'.$path;

        if ($remove_trailing_slash) {
            $url = rtrim($url, '/');
        }

        return $url;
    }

    public function getPayload(): array
    {
        return [];
    }

    //

    public function setToken(string $token): void
    {
        $this->bearer_token = $token;
    }

    public function getToken(): ?string
    {
        return $this->bearer_token;
    }

    public function getTokenType(): ?string
    {
        return null;
    }

    protected function hasToken(): bool
    {
        return ! empty($this->getToken());
    }

    //

    public function request_builder(): PendingRequest
    {
        $request_builder = Http::timeout($this->timeout);

        switch ($this->response_type) {
            case ResponseType::Json:
                $request_builder->acceptJson();
                break;

            default:
                break;
        }

        $request_builder->withBody($this->getPayload());

        switch ($this->body_type) {
            case BodyType::Form:
                $request_builder->asForm();
                break;

            case BodyType::Json:
                $request_builder->asJson();
                break;

            default:
                break;
        }

        if (in_array(QueryParams::class, class_uses_recursive(static::class))) {
            if ($this->hasQueryParams()) {
                $request_builder->withQueryParameters($this->getQueryParams());
            }
        }

        if (in_array(UserAgent::class, class_uses_recursive(static::class))) {
            if ($this->user_agent) {
                $request_builder->withUserAgent($this->user_agent);
            }
        }

        switch ($this->http_type) {
            case HttpType::GET:
                break;

            case HttpType::POST:
                break;

            default:
                break;
        }

        if ($this->hasToken()) {
            if ($tokenType = $this->getTokenType()) {
                $request_builder->withToken(
                    $this->getToken(),
                    $tokenType
                );
            } else {
                $request_builder->withToken(
                    $this->getToken()
                );
            }
        }

        return $request_builder;
    }

    public function submit(): Response
    {
        $request_builder = $this->request_builder();

        $response = $request_builder
            ->send(
                $this->http_type->value,
                $this->_url_build()
            );

        return $response;
    }

    public function parse_response(Response $response): mixed
    {
        if ($response->successful()) {
            return $response->body();
        }

        return null;
    }

    public function get_response()
    {
        return $this->parse_response($this->submit());
    }
}
