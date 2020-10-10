<?php

namespace app\modules\apiLogger\models;

use app\modules\apiLogger\helpers\ApiLoggerHelper;
use Exception;

class FileLoggerModel implements LoggerModelInterface
{
    private $method;

    private $url;

    private $date_started;

    private $date_ended;

    private $duration;

    private $bodyParams;

    private $headers;

    private $userId;

    private $response;

    private $appPlatform;

    private $appVersion;

    /**
     * @param $method
     * @param $url
     * @param $bodyParams
     * @param $headers
     * @return FileLoggerModel
     * @throws Exception
     */
    public static function loadFromRequestBodyParams($method, $url, $bodyParams, $headers): FileLoggerModel
    {
        $self = new self();
        $self->setMethod($method);
        $self->setUrl($url);
        $self->setBodyParams($bodyParams);
        $self->setDateStarted((new ApiLoggerHelper())->getCurrentDateWithMicro());

        if ($headers->has('App-Platform')) {
            $self->setAppPlatform($headers->get('App-Platform'));
        }
        if ($headers->has('App-Version')) {
            $self->setAppVersion($headers->get('App-Version'));
        }

        $self->setHeaders($headers->toArray());

        return $self;
    }

    public static function loadFromLogLine(string $line): self
    {
        $self = new self();
        $res = explode(']][[', $line, );

        if (count($res) !== 10) {
            throw new Exception('Некорректная строка в файле. Парсинг невозможен');
        }

        $self->setDateStarted($res[0]);
        $self->setDuration($res[1]);
        $self->setUserId($res[2]);
        $self->setAppPlatform($res[3]);
        $self->setAppVersion($res[4]);
        $self->setMethod($res[5]);
        $self->setUrl($res[6]);

        $self->setHeaders(ApiLoggerHelper::transformCompressToString($res[7]));
        $self->setBodyParams(ApiLoggerHelper::transformCompressToString($res[8]));
        $self->setResponse(ApiLoggerHelper::transformCompressToString($res[9]));

        return $self;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getBodyParams()
    {
        return $this->bodyParams;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getAppPlatform()
    {
        return $this->appPlatform;
    }

    /**
     * @return mixed
     */
    public function getAppVersion()
    {
        return $this->appVersion;
    }

    /**
     * @return string
     */
    public function getDateStarted(): string
    {
        return $this->date_started;
    }

    /**
     * @return mixed
     */
    public function getDateEnded()
    {
        return $this->date_ended;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }

    /**
     * @param mixed $date_ended
     */
    public function setDateEnded($date_ended): void
    {
        $this->date_ended = $date_ended;
        $this->calculateDuration();
    }

    public function calculateDuration(): void
    {
        $this->duration = (new ApiLoggerHelper())->diff($this->date_ended, $this->date_started);
    }

    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @param string $date_started
     */
    public function setDateStarted(string $date_started): void
    {
        $this->date_started = $date_started;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param array|string|null $appPlatform
     */
    public function setAppPlatform($appPlatform): void
    {
        $this->appPlatform = $appPlatform;
    }

    /**
     * @param array|string|null $appVersion
     */
    public function setAppVersion($appVersion): void
    {
        $this->appVersion = $appVersion;
    }

    /**
     * @param array $bodyParams
     */
    public function setBodyParams(array $bodyParams): void
    {
        $this->bodyParams = $bodyParams;
    }
}