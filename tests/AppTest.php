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
        putenv('RDS_HOSTNAME=MEMORY');
        putenv('RDS_PORT=');
        putenv('RDS_DB_NAME=');
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
        $name = 'app.admin123';
        $this->givenAClient();

        $this->client->request('PUT', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();

        $this->client->request('GET', '/roles/' . $name);

        $this->thenTheResponseIsSuccess();
    }

    public function testDeleteRemovesRole()
    {
        $name = 'app.special-news-editor';
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

        $roles = ['app.user', 'app.administrator','app.editor', 'app.super'];
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

    public function testAddMemberToRole()
    {
        $role = 'app:xxx.super';
        $member = 'urn:app:account.user.999';

        $this->givenAClient();
        $this->givenARoleExists($role);

        $this->client->request('PUT', '/roles/' . $role . '/members/' . $member);
        $this->thenTheResponseIsSuccess();

        $this->client->request('GET', '/roles/' . $role . '/members/' . $member);
        $this->thenTheResponseIsSuccess();
    }

    public function testGetMemberBelongsToRoleFailsWhenItDoesnt()
    {
        $role = 'app:yyy.browser';
        $member = 'urn:app:account.user.111';

        $this->givenAClient();
        $this->client->request('GET', '/roles/' . $role . '/members/' . $member);
        $this->thenTheResponseIs404();
    }

    public function testRemoveMemberFromRole()
    {
        $role = 'service:abc.editor';
        $member = 'urn:app:account.user.88';

        $this->givenAClient();
        $this->givenARoleExists($role);

        $this->client->request('PUT', '/roles/' . $role . '/members/' . $member);
        $this->thenTheResponseIsSuccess();

        $this->client->request('GET', '/roles/' . $role . '/members/' . $member);
        $this->thenTheResponseIsSuccess();

        $this->client->request('DELETE', '/roles/' . $role . '/members/' . $member);
        $this->thenTheResponseIsSuccess();

        $this->client->request('GET', '/roles/' . $role . '/members/' . $member);
        $this->thenTheResponseIs404();
    }


    public function testListRoleMembers()
    {
        $role = 'app:cool-guys.editor';
        $this->givenAClient();
        $this->givenARoleExists($role);

        $this->client->request('GET', '/roles/' . $role . '/members');
        $this->thenTheResponseIsSuccess();
    }

    public function testListRoleMembersFailsWhenRoleIsMissing()
    {
        $role = 'app:cool-guys.editor';
        $this->givenAClient();

        $this->client->request('GET', '/roles/' . $role . '/members');
        $this->thenTheResponseIs404();
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