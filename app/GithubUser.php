<?php
namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class GithubUser
{
  private $base_url = "https://api.github.com/users/";

  private $id = null;
  private $login = null;
  private $html_url = null;
  private $avatar_url = null;
  private $url = null;
  private $repos_url = null;

  private $repositories = [];

  private $usernameRegExp = "/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/i";

  public function __construct($username) {
    //Simple validation  for usernames/logins:
    if (!$this->validateUserName($username)) {
      throw new \Exception("Invalid Username: {$username}", 1);
    }
    $this->login = $username;

    //Load user info
    if(!$this->getUserFromRedis($username)) {
      $this->getUserFromGithub($username);
    }
  // Validate Required user info
    $this->validateRequiredFields();
  }

  private function validateRequiredFields() {
    foreach ($this as $key=>$value) {
      if (isset($this->$key) && is_null($value) ) {
        throw new \Exception("Missing field.{$key} is Required", 1);
      }
    }
  }

  private function validateUserName($username):bool {
    return preg_match($this->usernameRegExp, $username);
  }

  private function getUserFromGithub() {
    // https://api.github.com/users/raphaelsar
    $client = new Client([
        'base_uri' => $this->base_url,
        'timeout'  => 2.0,
    ]);

    try {
      $response = $client->request('GET', $this->login);
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      $response = $e->getResponse();
      throw new \Exception("Error retrieving user info from Github:
          {$response->getBody()->getContents()}", $e->getCode()
      );
    }
    $userInfo = $response->getBody();
    $this->loadFromJson($userInfo);
    $this->saveUserInRedis($userInfo);
    unset($client);
  }

  private function loadFromJson($githubJson) {
    $jsonObj = json_decode($githubJson);
    foreach($jsonObj as $property => $value) {
      $this->$property = $value;
    }
  }

  private function getUserFromRedis($username) {
    try {
        $user = Redis::get($username);
        $this->loadFromJson($user);
    } catch (\Exception $e) {
      return false;
    }
  }

  private function saveUserInRedis($userInfo) {
    try{
      Redis::set($this->login, $userInfo);
    } catch(\Exception $e) {
      throw new \Exception("Error Saving User in REDIS", 4);
    }
  }

  public function toJson()
  {
    $userArray = [
      'login' => $this->login,
      'id' => $this->id,
      'avatar_url' => $this->avatar_url,
      'html_url' => $this->html_url,
    ];

    return json_encode($userArray);
  }

  public function getGithubRepository() {
    if(!$this->getUserRepositoryFromRedis($this->login)) {
      $this->getUserRepositoryFromGithub($this->login);
    }
    return $this;
  }

  private function getUserRepositoryFromGithub() {
    // https://api.github.com/users/raphaelsar/repos
    $client = new Client([
        'base_uri' => $this->repos_url,
        'timeout'  => 2.0,
    ]);

    try {
      $response = $client->request('GET');
    } catch (\GuzzleHttp\Exception\ClientException $e) {
      $response = $e->getResponse();
      throw new \Exception("Error retrieving user info from Github:
          {$response->getBody()->getContents()}", $e->getCode()
      );
    }
    $reposInfo = $response->getBody();
    $this->loadReposFromJson($reposInfo);
    $this->saveUserReposInRedis($reposInfo);
    unset($client);
  }

  private function loadReposFromJson($githubJson) {
      $this->repositories = [];
      $jsonObj = json_decode($githubJson);
      foreach($jsonObj as $jsonRepository) {
        $repo = new GithubRepository($jsonRepository, $this->login);
        array_push($this->repositories, $repo->toJson());
      }
  }

  private function getUserRepositoryFromRedis() {
    $repos = $this->login . "-repos";
    try {
        $reposInfo = Redis::get($repos);
        $this->loadReposFromJson($reposInfo);
    } catch (\Exception $e) {
      return false;
    }
  }

  private function saveUserReposInRedis($userInfo) {
    $repos = $this->login . "-repos";
    try{
      Redis::set($this->login, $userInfo);
    } catch(\Exception $e) {
      throw new \Exception("Error Saving User in REDIS", 4);
    }
  }

  public function reposToJson() {
    return json_encode($this->repositories);
  }
}
?>
