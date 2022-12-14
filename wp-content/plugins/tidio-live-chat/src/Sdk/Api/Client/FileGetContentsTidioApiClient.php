<?php

class FileGetContentsTidioApiClient implements TidioApiClient
{
    /**
     * @var string[]
     */
    private $headers;

    /**
     * @param string[] $additionalHeaders
     */
    public function __construct($additionalHeaders = [])
    {
        $this->headers = array_merge(
            ['Accept: application/json'],
            $additionalHeaders
        );
    }

    /**
     * @inerhitDoc
     */
    public function sendPostRequest($path, $data = [])
    {
        $url = TidioLiveChatConfig::getApiUrl() . $path;
        $content = http_build_query($data);
        $headers = array_merge([
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($content)
        ], $this->headers);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => $this->prepareRequestHeaders($headers),
                'content' => http_build_query($data),
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        $responseData = $this->parseResponseData($response);
        $this->validateResponse($responseData, $http_response_header);

        return $responseData;
    }

    /**
     * @inerhitDoc
     */
    public function sendGetRequest($path)
    {
        $url = TidioLiveChatConfig::getApiUrl() . $path;
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => $this->prepareRequestHeaders($this->headers),
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        $responseData = $this->parseResponseData($response);
        $this->validateResponse($responseData, $http_response_header);

        return $responseData;
    }

    /**
     * @param string[] $headers
     * @return string
     */
    private function prepareRequestHeaders($headers)
    {
        $headerString = '';
        foreach ($headers as $header) {
            $headerString .= $header . "\r\n";
        }

        return $headerString;
    }

    /**
     * @param string|false $response
     * @return array<string, mixed>
     */
    private function parseResponseData($response)
    {
        if (false === $response) {
            return [];
        }

        return json_decode($response, true);
    }

    /**
     * @param array<string, mixed> $responseData
     * @param string[] $responseHeaders
     * @throws TidioApiException
     */
    private function validateResponse($responseData, $responseHeaders)
    {
        $statusCode = $this->parseStatusCodeFromHeaders($responseHeaders);
        if ($statusCode === 401) {
            throw TidioApiException::withUnauthorizedErrorCode();
        } elseif ($statusCode < 200 || $statusCode >= 300) {
            if (isset($responseData['error'])) {
                throw TidioApiException::withErrorCode($responseData['error']);
            }

            throw TidioApiException::withUnknownErrorCode();
        }
    }

    /**
     * @param string[] $responseHeaders
     * @return int
     */
    private function parseStatusCodeFromHeaders($responseHeaders)
    {
        $statusCodeFallback = 500;
        if (!isset($responseHeaders[0])) {
            // cannot retrieve status code from headers, return 500 as fallback
            return $statusCodeFallback;
        }

        $responseStatusHeader = $responseHeaders[0];
        preg_match('/^HTTP\/\S*\s(\d{3})\s.+$/', $responseStatusHeader, $statusCodeResult);

        $statusCode = end($statusCodeResult);
        return $statusCode ? (int) $statusCode : $statusCodeFallback;
    }
}