<?php

use Silex\WebTestCase;

/**
 * @author timrodger
 * Date: 18/03/15
 */
class UnavailableAppTest extends WebTestCase
{
    private $client;

    public function createApplication()
    {
        putenv('STORE_DSN=UNAVAILABLE');
        return require __DIR__.'/../src/app.php';
    }

    public function testGetFailsWhenUnavailable()
    {
        $name = 'app.admin';
        $this->givenAClient();

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIs503();
    }

    public function testPutFailsWhenUnavailable()
    {
        $name = 'app.admin';
        $this->givenAClient();

        $this->client->request('PUT', '/roles/' . $name);

        $this->thenTheResponseIs503();
    }

    public function testDeleteFailsWhenUnavailable()
    {
        $name = 'app.news-editor';
        $this->givenAClient();

        $this->client->request('DELETE', '/roles/' . $name);

        $this->thenTheResponseIs503();

    }

    public function testListFailsWhenUnavailable()
    {
        $this->givenAClient();

        $this->client->request('GET', '/roles');

        $this->thenTheResponseIs503();

    }

    private function givenAClient()
    {
        $this->client = $this->createClient();
    }

    private function thenTheResponseIs503()
    {
        $this->assertSame(503, $this->client->getResponse()->getStatusCode());
    }


    protected function assertResponseContents($expected_body)
    {
        $this->assertSame($expected_body, $this->client->getResponse()->getContent());
    }
}