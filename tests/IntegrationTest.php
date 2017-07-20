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
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();

        $this->assertRoleDoesNotExist($role);
    }

    public function testGetSucceedsForRole()
    {
        $role = 'app.test.'  . rand(0, PHP_INT_MAX);

        $this->givenAClient();
        $this->givenARoleExists($role);

        $this->assertRoleExists($role);
    }

    public function testListRespondsWithArrayOfRoles()
    {
        $this->givenAClient();

        $this->client->request('GET', '/roles');

        $this->thenTheResponseIsSuccess();

        $actual = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue(is_array($actual));
    }

    public function testDeleteRemovesRole()
    {
        $role = 'app.news-editor';
        $this->givenAClient();
        $this->givenARoleExists($role);

        $this->client->request('DELETE', '/roles/' . $role);

        $this->thenTheResponseIsSuccess();

        $this->assertRoleDoesNotExist($role);
    }

    public function testPutAddsARole()
    {
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();

        $this->client->request('PUT', '/roles/' . $role);

        $this->thenTheResponseIsSuccess();

        $this->assertRoleExists($role);
    }

    public function testGetMembers()
    {
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();

        $this->givenARoleExists($role);

        $this->client->request('GET', '/roles/' . $role . '/members');

        $this->thenTheResponseIsSuccess();
    }

    public function testGetMembersFailsForMissingRole()
    {
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();

        $this->client->request('GET', '/roles/' . $role . '/members');

        $this->thenTheResponseIs404();
    }

    public function testAddMemberToRole()
    {
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $member = 'urn:app:account.user.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();
        $this->givenARoleExists($role);

        $this->client->request('PUT', '/roles/' . $role . '/members/' . $member);

        $this->thenTheResponseIsSuccess();

        $this->assertMemberBelongsToRole($role, $member);

        // adding it a second time also succeeds
        $this->client->request('PUT', '/roles/' . $role . '/members/' . $member);

        $this->thenTheResponseIsSuccess();
    }

    public function testMemberBelongsToRole(){
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $member = 'urn:app:account.user.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();
        $this->givenARoleExists($role);
        $this->givenMemberBelongsToRole($role, $member);

        $this->assertMemberBelongsToRole($role, $member);
    }

    public function testMemberBelongsToRoleFailsWhenMissing(){
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $member = 'urn:app:account.user.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();
        $this->givenARoleExists($role);

        $this->assertMemberDoesNotBelongToRole($role, $member);
    }

    public function testRemoveMemberFromRole()
    {
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $member = 'urn:app:account.user.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();
        $this->givenARoleExists($role);
        $this->givenMemberBelongsToRole($role, $member);

        $this->client->request('DELETE', '/roles/' . $role . '/members/' . $member);

        $this->thenTheResponseIsSuccess();

        $this->assertMemberDoesNotBelongToRole($role, $member);
    }

    public function testRemoveMemberFromRoleFailsForMissingRole()
    {
        $role = 'app.admin.' . rand(0, PHP_INT_MAX);
        $member = 'urn:app:account.user.' . rand(0, PHP_INT_MAX);
        $this->givenAClient();

        $this->client->request('DELETE', '/roles/' . $role . '/members/' . $member);

        $this->thenTheResponseIs404();
    }

    private function assertRoleExists($role)
    {
        $this->client->request('GET', '/roles/' . $role);

        $this->thenTheResponseIsSuccess();
    }

    private function assertRoleDoesNotExist($role)
    {
        $this->client->request('GET', '/roles/' . $role);

        $this->thenTheResponseIs404();
    }

    private function assertMemberBelongsToRole($role, $member)
    {
        $this->client->request('GET', '/roles/' . $role . '/members/' . $member);

        $this->thenTheResponseIsSuccess();
    }

    private function assertMemberDoesNotBelongToRole($role, $member)
    {
        $this->client->request('GET', '/roles/' . $role . '/members/' . $member);

        $this->thenTheResponseIs404();
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

    private function givenARoleExists($role)
    {
        $this->client->request('PUT', '/roles/' . $role);
    }

    private function givenMemberBelongsToRole($role, $member)
    {
        $this->client->request('PUT', '/roles/' . $role . '/members/' . $member);

        $this->thenTheResponseIsSuccess();
    }

}