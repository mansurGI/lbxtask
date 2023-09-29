<?php

namespace App\Tests;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApiTestCase extends KernelTestCase
{
    #[ArrayShape(['code' => "int", 'content' => "array", 'raw' => "string"])]
    public function request($method, $uri, $payload = null, $mocks = [], $parameters = [], $files = []): array
    {
        foreach ($mocks as $id => $object) {
            self::getContainer()->set($id, $object);
        }

        /** @var KernelBrowser $client */
        $client = self::getContainer()->get('test.client');

        $client->request($method, $uri, $parameters, $files, ['HTTP_ACCEPT' => 'application/json'], $payload);

        $response = $client->getResponse();

        try {
            $content = json_decode((empty($response->getContent())) ? '[]' : $response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            var_dump($e->getMessage(), $response->getContent(), $e);
        }

        return [
            'code' => $response->getStatusCode(),
            'content' => $content,
            'raw' => $response->getContent(),
        ];
    }

    public function getDoctrine()
    {
        return self::getContainer()->get('doctrine');
    }
}