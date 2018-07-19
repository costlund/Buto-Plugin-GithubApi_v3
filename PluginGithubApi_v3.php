<?php
/**
 * Github API via curl.
 */
class PluginGithubApi_v3{
  /**
   * 
    type: widget
    data:
      plugin: 'github/api_v3'
      method: demo
      data:
        username: _
        token: _
        repo:
          user: _
          name: _
   */
  public function widget_demo($data){
    wfPlugin::includeonce('wf/array');
    /**
     * Widget data.
     */
    $data = new PluginWfArray($data);
    /**
     * Methods.
     */
    if($data->get('data/method')=='ReposTags'){
      $repos_tags = $this->getReposTags($data->get('data/username'), $data->get('data/token'), $data->get('data/repo/user'), $data->get('data/repo/name'));
    }elseif($data->get('data/method')=='Repo'){
      $github_data = $this->getRepo($data->get('data/username'), $data->get('data/token'), $data->get('data/repo/user'), $data->get('data/repo/name'));
    }elseif($data->get('data/method')=='RepoCommits'){
      $github_data = $this->getRepoCommits($data->get('data/username'), $data->get('data/token'), $data->get('data/repo/user'), $data->get('data/repo/name'));
    }elseif($data->get('data/method')=='UserRepos'){
      $github_data = $this->getUserRepos($data->get('data/username'), $data->get('data/token'));
    }else{
      exit('Param data/method does not exist or is incorrect.');
    }
    /**
     * Print data.
     */
    echo '<pre>';
    print_r(sizeof($github_data));
    echo '<hr>';
    print_r($github_data);
    echo '</pre>';
  }
  /**
   * Get repo tags.
   */
  public function getReposTags($username, $token, $repo_user, $repo_name){
    $repos = $this->curl('https://api.github.com/repos/'.$repo_user.'/'.$repo_name.'/tags', $username, $token);
    return $repos;
  }
  /**
   * Get repo commits.
   */
  public function getRepoCommits($username, $token, $repo_user, $repo_name){
    $repos = $this->curl('https://api.github.com/repos/'.$repo_user.'/'.$repo_name.'/commits', $username, $token);
    return $repos;
  }
  /**
   * Get user repos.
   */  
  public function getUserRepos($username, $token){
    $repos = $this->curl('https://api.github.com/user/repos?page=1&per_page=100', $username, $token);
    return $repos;
  }
  /**
   * Get repos by user.
   */
  public function getRepo($username, $token, $repo_user, $repo_name){
    $repos = $this->curl('https://api.github.com/repos/'.$repo_user.'/'.$repo_name.'', $username, $token);
    return $repos;
  }
  /**
   * Curl request.
   */
  public function curl($url, $username, $token)
  {
    $ch = curl_init();
    $access = $username.':'.$token;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Agent curl');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERPWD, $access);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode(trim($output), true);
    return $result;
  }
}
