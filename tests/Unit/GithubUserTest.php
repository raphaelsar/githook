<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\GithubUser;

class GithubUserTest extends TestCase
{
  protected $userMock;

  protected function setUp(): void {
    $this->userMock = $this->getMockBuilder('App\GithubUser')
        ->disableOriginalConstructor()
        ->setMethods(null)
        ->getMock();;
}

  public function invokeMethod(&$object, $methodName, array $parameters = array()) {
      $reflection = new \ReflectionClass(get_class($object));
      $method = $reflection->getMethod($methodName);
      $method->setAccessible(true);

      return $method->invokeArgs($object, $parameters);
  }

  public function usernameDataProvider() {
      return [
          ['saponeis', true],
          ['Paulo Deonias', false],
          ['11121', true],
          ['raphael-saponeis', true],
          ['raphael--saponeis', false],
          ['raphael--', false],
          ['raphael.saponeis', false],
          ['raphael_saponeis', false],
          ['.raphael_saponeis', false],
          ['.raphael.', false],
      ];
  }

  /**
   * @dataProvider usernameDataProvider
   */
    public function testValidateUserName($username, $expected) {
      $res = $this->invokeMethod($this->userMock, 'validateUserName', [$username]);
      $this->assertEquals($res , $expected);
    }

    public function testLoadJsonWithMinimalRequiriments() {
      $minimalArray = [
        "login" => 'saponeis',
        "id" => 123,
        "avatar_url" => 'http://avatar_example.com',
        "html_url" => 'http://html_example.com',
        "url" => 'http://url_example.com',
        "repos_url" => 'http://repos_example.com',
      ];

      $this->loadJsonTest($minimalArray);
    }

    public function testLoadJsonWithExtraFieldsInJson() {
      $extraFieldsArray = [
        "login" => 'saponeis',
        "id" => 123,
        "avatar_url" => 'http://avatar_example.com',
        "html_url" => 'http://html_example.com',
        "url" => 'http://url_example.com',
        "repos_url" => 'http://repos_example.com',
        "name" => 'Raphael Ramos',
      ];

      $this->loadJsonTest($extraFieldsArray);
    }

    /**
     * @expectedException Exception
     */
    public function testLoadJsonWithoutMinimalRequiriments() {
      $minimalArray = [
        "login" => 'saponeis',
        "id" => 123,
        "avatar_url" => 'teste'
      ];
      $this->loadJsonTest($minimalArray);
    }

    private function loadJsonTest($payload) {
      $json = json_encode($payload);

      $this->userMock->loadFromJson($json);
      $this->userMock->validateRequiredFields();

      $responseArray = $this->userMock->toArray();

      $this->assertEquals($payload['login'] , $responseArray['login']);
      $this->assertEquals($payload['id'] , $responseArray['id']);
      $this->assertEquals($payload['avatar_url'] , $responseArray['avatar_url']);
      $this->assertEquals($payload['html_url'] , $responseArray['html_url']);
    }

}
