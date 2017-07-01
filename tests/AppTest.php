<?php

use Silex\WebTestCase;

/**
 * @author timrodger
 * Date: 18/03/15
 */
class AppTest extends WebTestCase
{
    private $client;

    public function createApplication()
    {
        putenv('STORE_DSN=MEMORY');
        return require __DIR__.'/../src/app.php';
    }

    public function testPutAddsARole()
    {
        $name = 'app.admin';
        $this->givenAClient();

        $this->client->request('PUT', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();
    }

    public function testDeleteAlwaysRespondsWithSuccess()
    {
        $this->givenAClient();

        $this->client->request('DELETE', '/roles/app.super');

        $this->thenTheResponseIsSuccess();
    }

    public function testListRespondsWithArrayOfRoles()
    {
        $this->givenAClient();

        $this->client->request('GET', '/roles');

        $this->thenTheResponseIsSuccess();

        $this->assertResponseContents(json_encode([], JSON_UNESCAPED_SLASHES));
    }

    private function givenAClient()
    {
        $this->client = $this->createClient();
    }

    private function givenARoleExists($name)
    {
        $this->client->request('PUT', $name);
    }

    private function thenTheResponseIsSuccess()
    {
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    private function thenTheResponseIs404()
    {
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    protected function assertResponseContents($expected_body)
    {
        $this->assertSame($expected_body, $this->client->getResponse()->getContent());
    }
}