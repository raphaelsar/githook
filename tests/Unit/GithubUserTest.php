<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\GithubUser;

class GithubUserTest extends TestCase
{
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
      $userMock = $this->getMockBuilder('App\GithubUser')
          ->disableOriginalConstructor()
          ->setMethods(null)
          ->getMock();

      $res = $userMock->validateUserName($username);
      $this->assertEquals($res , $expected);
    }

    public function testLoadJsonWithMinimalRequiriments() {
      $userMock = $this->getMockBuilder('App\GithubUser')
          ->disableOriginalConstructor()
          ->setMethods(null)
          ->getMock();
    }
}
