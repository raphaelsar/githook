<?php
namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class GithubUser
{
  private $base_url = "https://api.github.com/users/";

  private $login = null;
  private $id = null;
  private $nodeId = null;
  private $avatar_url = null;
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
    'login', 'id', 'html_url', 'avatar_url', 'url', 'repos_url',
  ];

  private function validateRequiredFields() {
    foreach ($this->requiredFields as $field) {
      if (is_null($this->$field)) {
        throw new \Exception("Missing field.{$field} is Required", 1);
      }
    }
  }
}
?>
