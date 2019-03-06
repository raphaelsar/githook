<?php
namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class GithubRepository
{
  private $id = null;
  private $node_id = null;
  private $name = null;
  private $full_name = null;
  private $private = null;
  private $owner =null;
  private $html_url = null;
  private $description = null;
  private $fork = null;
  private $url = null;
  private $forks_url = null;
  private $keys_url = null;
  private $collaborators_url = null;
  private $teams_url = null;
  private $hooks_url = null;
  private $issue_events_url = null;
  private $events_url = null;
  private $assignees_url = null;
  private $branches_url = null;
  private $tags_url = null;
  private $blobs_url = null;
  private $git_tags_url = null;
  private $git_refs_url = null;
  private $trees_url = null;
  private $statuses_url = null;
  private $languages_url = null;
  private $stargazers_url = null;
  private $contributors_url = null;
  private $subscribers_url = null;
  private $subscription_url = null;
  private $commits_url = null;
  private $git_commits_url = null;
  private $comments_url = null;
  private $issue_comment_url = null;
  private $contents_url = null;
  private $compare_url = null;
  private $merges_url = null;
  private $archive_url = null;
  private $downloads_url = null;
  private $issues_url = null;
  private $pulls_url = null;
  private $milestones_url = null;
  private $notifications_url = null;
  private $labels_url = null;
  private $releases_url = null;
  private $deployments_url = null;
  private $created_at = null;
  private $updated_at = null;
  private $pushed_at = null;
  private $git_url = null;
  private $ssh_url = null;
  private $clone_url = null;
  private $svn_url = null;
  private $homepage = null;
  private $size = null;
  private $stargazers_count = null;
  private $watchers_count = null;
  private $language = null;
  private $has_issues = null;
  private $has_projects = null;
  private $has_downloads = null;
  private $has_wiki = null;
  private $has_pages = null;
  private $forks_count = null;
  private $mirror_url = null;
  private $archived = null;
  private $open_issues_count = null;
  private $license =null;
  private $forks = null;
  private $open_issues = null;
  private $watchers = null;
  private $default_branch = null;

  private $requiredFields = [
    "id", "name" , "html_url"
  ];

  public function __construct($jsonRepository, $username) {
    $this->loadFromJson($jsonRepository);
    $this->owner = $username;
    $this->validateRequiredFields();
  }

  private function validateRequiredFields() {

    foreach ($this->requiredFields as $field) {
      if (is_null($this->$field)) {
        throw new \Exception("Missing field.{$field} is Required", 1);
      }
    }
  }
  private function loadFromJson($githubJson) {
      foreach($githubJson as $property => $value) {

          if( is_null( $this->$property ) ) {
            $this->$property = $value;
          }
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
}
?>
