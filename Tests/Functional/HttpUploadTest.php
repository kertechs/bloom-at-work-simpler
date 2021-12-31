<?php

declare(strict_types=1);

namespace BloomAtWork\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class HttpUploadTest extends WebTestCase
{
    private KernelBrowser $client;

    private string $validCsvFilePath;
    private string $invalidCsvFilePath;

    public function setUp() :void
    {
        $this->client = static::createClient();

        $this->validCsvFilePath = __DIR__ . '/../csv/my-test-file-with-bad-answers.csv';
        $this->invalidCsvFilePath = __DIR__ . '/../csv/my-bad-test-file.csv';
        $this->invalidCsvExtensionFilePath = __DIR__ . '/../csv/my-test-file.txt';
    }

    public function testGetStatisticsAfterUploadingCsvWithValidContent()
    {
        $this->client->request(
            'POST',
            '/question-stats/csv/upload',
            [],
            ['csvfile' => new UploadedFile($this->validCsvFilePath, 'file.csv')]
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());

        $expectedStatistics = json_decode(json_encode( [
          'question' => [
              'label' => '# Coucou Hibou',
              'statistics' => [
                  'min' => 1.0,
                  'max' => 2.0,
                  'mean' => 1.50,
              ]
          ]
        ]));
        $responseStatistics = json_decode($response->getContent());

        $this->assertEquals($expectedStatistics, $responseStatistics);
    }

    public function testGetErrorAfterUploadingCsvWithInvalidContent()
    {
        $this->client->request(
            'POST',
            '/question-stats/csv/upload',
            [],
            ['csvfile' => new UploadedFile($this->invalidCsvFilePath, 'file.csv')]
        );

        $response = $this->client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testResponseErrorWhenNoFileIsUploaded()
    {
        $this->client->request(
            'POST',
            '/question-stats/csv/upload',
            [],
            []
        );

        $response = $this->client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
        $this->assertStringContainsString('No file uploaded', $response->getContent());
    }

    public function testResponseErrorWhenUploadedFileIsNotACsvFile()
    {
        $this->client->request(
            'POST',
            '/question-stats/csv/upload',
            [],
            ['csvfile' => new UploadedFile($this->invalidCsvExtensionFilePath, 'file.txt')]
        );

        $response = $this->client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
        $this->assertStringContainsString('File is not valid', $response->getContent());
    }
}
