<?php
namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class GithubRepository
{
  private $id = null;
  private $name = null;
  private $html_url = null;
  private $description = null;

  private $requiredFields = [
    "id", "name" , "html_url"
  ];

  public function __construct($jsonRepository, $username) {
    $this->loadFromJson($jsonRepository);
    $this->owner = $username;
    $this->validateRequiredFields();
  }

  private function validateRequiredFields() {
    foreach ($this as $key=>$value) {
      if (isset($this->$key) && is_null($value) ) {
        throw new \Exception("Missing field.{$key} is Required", 1);
      }
    }
  }

  private function loadFromJson($githubJson) {
    foreach($githubJson as $property => $value) {
      $this->$property = $value;
    }
  }

  public function toJson() {
    $repoArray = [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'html_url' => $this->html_url,
    ];

    return json_encode($repoArray);

  }
}
?>
