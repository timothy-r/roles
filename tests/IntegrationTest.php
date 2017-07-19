<?php


use Silex\WebTestCase;

/**
 * @author timrodger
 * Date: 18/03/15
 */
class IntegrationTest extends WebTestCase
{
    private $client;

    public function createApplication()
    {
        return require __DIR__.'/../src/app.php';
    }

    public function testGetFailsForMissingRole()
    {
        $name = 'app.admin.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIs404();
    }

    public function testGetSucceedsForRole()
    {
        $name = 'app.test.'  . rand(0, PHP_INT_MAX);

        $this->givenAClient();
        $this->givenARoleExists($name);

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();
    }

    public function testListRespondsWithArrayOfRoles()
    {
        $this->givenAClient();

        $this->client->request('GET', '/roles');

        $this->thenTheResponseIsSuccess();

        $actual = json_decode($this->client->getResponse()->getContent(), true);

        var_dump($actual);

        $this->assertTrue(is_array($actual));
    }

    public function testDeleteRemovesRole()
    {
        $name = 'app.news-editor';
        $this->givenAClient();
        $this->givenARoleExists($name);

        $this->client->request('DELETE', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIs404();
    }

    public function testPutAddsARole()
    {
        $name = 'app.admin.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();

        $this->client->request('PUT', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();

        $this->client->request('PUT', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();


        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();
    }

    private function givenAClient()
    {
        $this->client = $this->createClient();
    }

    private function thenTheResponseIsSuccess()
    {
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    private function thenTheResponseIs404()
    {
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    private function givenARoleExists($name)
    {
        $this->client->request('PUT', '/roles/' . $name);
    }

}