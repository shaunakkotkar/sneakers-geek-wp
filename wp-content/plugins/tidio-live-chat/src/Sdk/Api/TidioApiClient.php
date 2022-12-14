<?php

interface TidioApiClient
{
    /**
     * @param string $path
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws TidioApiException
     */
    public function sendPostRequest($path, $data = []);

    /**
     * @param string $path
     * @return array<string, mixed>
     * @throws TidioApiException
     */
    public function sendGetRequest($path);
}