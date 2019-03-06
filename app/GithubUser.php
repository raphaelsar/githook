<?php
namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class GithubUser
{
  private $base_url = "https://api.github.com/users/";

  private $login = null;
  private $id = null;
  private $node_id = null;
  private $avatar_url = null;
  private $gravatar_id = null;
  private $url = null;
  private $html_url = null;
  private $followers_url = null;
  private $following_url = null;
  private $gists_url = null;
  private $starred_url = null;
  private $subscriptions_url = null;
  private $organizations_url = null;
  private $repos_url = null;
  private $events_url = null;
  private $received_events_url = null;
  private $type = null;
  private $site_admin = null;
  private $name = null;
  private $company = null;
  private $blog = null;
  private $location = null;
  private $email = null;
  private $hireable = null;
  private $bio = null;
  private $public_repos = null;
  private $public_gists = null;
  private $followers = null;
  private $following = null;
  private $created_at = null;
  private $updated_at = null;
  private $requiredFields = [
    'id', 'login', 'html_url', 'avatar_url', 'url', 'repos_url',
  ];
  private $usernameRegExp = "/^[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}$/i";

  public function __construct($username) {
    //Simple validation  for usernames/logins:
    if (!$this->validateUserName($username)) {
      throw new \Exception("Invalid Username: {$username}", 1);
    }
    $this->login = $username;

    //Load user info
    $this->getUserFromGithub($username);

  // Validate Required user info
    $this->validateRequiredFields();
  }

  private function validateRequiredFields() {
    foreach ($this->requiredFields as $field) {
      if (is_null($this->$field)) {
        throw new \Exception("Missing field.{$field} is Required", 1);
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
    $body = $response->getBody();
    $this->loadFromJson($body);
  }

  private function loadFromJson($githubJson) {
      $jsonObj = json_decode($githubJson);
      foreach($jsonObj as $property => $value) {
          if( is_null( $this->$property ) ) {
            $this->$property = $value;
          }
      }
  }

}
?>