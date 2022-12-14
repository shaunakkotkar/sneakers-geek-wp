<?php

class CurlTidioApiClient implements TidioApiClient
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
            [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            $additionalHeaders
        );
    }

    /**
     * @inerhitDoc
     */
    public function sendPostRequest($path, $data = [])
    {
        $url = TidioLiveChatConfig::getApiUrl() . $path;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $response = curl_exec($ch);
        $responseInfo = curl_getinfo($ch);
        curl_close($ch);

        $responseData = $this->parseResponseData($response, $responseInfo);
        $this->validateResponse($responseData, $responseInfo);

        return $responseData;
    }

    /**
     * @inerhitDoc
     */
    public function sendGetRequest($path)
    {
        $url = TidioLiveChatConfig::getApiUrl() . $path;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $response = curl_exec($ch);
        $responseInfo = curl_getinfo($ch);
        curl_close($ch);

        $responseData = $this->parseResponseData($response, $responseInfo);
        $this->validateResponse($responseData, $responseInfo);

        return $responseData;
    }

    /**
     * @param string|false $response
     * @param array<string, mixed> $responseInfo
     * @return array<string, mixed>
     */
    private function parseResponseData($response, $responseInfo)
    {
        $headerSize = $responseInfo['header_size'];
        $responseBody = substr($response, $headerSize);
        $responseData = json_decode($responseBody, true);

        if (false === $responseData) {
            return [];
        }

        return $responseData;
    }

    /**
     * @param array<string, mixed> $responseData
     * @param array<string, mixed> $responseInfo
     * @throws TidioApiException
     */
    private function validateResponse($responseData, $responseInfo)
    {
        $statusCode = $responseInfo['http_code'];
        if ($statusCode === 401) {
            throw TidioApiException::withUnauthorizedErrorCode();
        } elseif ($statusCode < 200 || $statusCode >= 300) {
            if (isset($responseData['error'])) {
                throw TidioApiException::withErrorCode($responseData['error']);
            }

            throw TidioApiException::withUnknownErrorCode();
        }
    }
}