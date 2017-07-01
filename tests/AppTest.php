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

    public function testGetFailsForMissingRole()
    {
        $name = 'app.admin';
        $this->givenAClient();

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIs404();
    }

    public function testGetSucceedsForRole()
    {
        $name = 'app.test';
        $this->givenAClient();
        $this->givenARoleExists($name);

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();
    }

    public function testPutAddsARole()
    {
        $name = 'app.admin';
        $this->givenAClient();

        $this->client->request('PUT', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();
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

    public function testDeleteAlwaysRespondsWithSuccess()
    {
        $this->givenAClient();

        $this->client->request('DELETE', '/roles/app.super');

        $this->thenTheResponseIsSuccess();
    }

    public function testListRespondsWithArrayOfRoles()
    {
        $this->givenAClient();

        $roles = ['app.user', 'app.admin','app.editor', 'app.super'];
        foreach($roles as $name) {
            $this->givenARoleExists($name);
        }

        $this->client->request('GET', '/roles');

        $this->thenTheResponseIsSuccess();

        $actual = json_decode($this->client->getResponse()->getContent(), true);

        foreach($actual as $actual_name){
            $this->assertTrue(in_array($actual_name, $roles));
        }
    }

    private function givenAClient()
    {
        $this->client = $this->createClient();
    }

    private function givenARoleExists($name)
    {
        $this->client->request('PUT', '/roles/' . $name);
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