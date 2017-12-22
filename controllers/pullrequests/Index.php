<?php

use GitHubSA\RepoPullRequests;


/**
 * Index
 *
 * @package GitHub SA
 */
class Index extends CoreOGraphy\BaseController {
    
    /* @var $owner String The owner of the repostitory */
    protected $owner = '';
    
    
    /* @var $repo String The repository */
    protected $repo = '';
    
    
    /* @var $action String The action: view|download */
    protected $action = '';
    
    
    /* @var $how_many int How many balanced results we want to obtain */
    protected $how_many = '';
    
    
    
    /**
     * __construct
     *
     * @param $owner String The owner of the repo
     * @param $repo String The repo
     * @param $action String The action to be performed (view|download)
     *
     * @package GitHub SA
     */    
    public function __construct ($owner, $repo, $how_many, $action) {
    
        // Bind the constructor variables with the class vars
        $this->owner = $owner;
        $this->repo = $repo;
        $this->action = $action;
        $this->how_many = $how_many ? $how_many : PHP_INT_MAX;
    
    
        // Delegate on parent
        parent::__construct ();
    }
    
    
    /**
     * handleRequest
     *
     * @package GitHub SA
     */
    public function handleRequest () {
    
        /* @var $pull_requests Array */
        $pull_requests = [];
        
        
        /* @var $anchors Array */
        $anchors = [];
        
        
        /* @var positives Array Store the positive items */
        $positives = array ();
        
        
        /* @var negatives Array Store the negative items */
        $negatives = array ();
        
        
        /* @var $base_github_url String */
        $base_github_url = 'https://github.com/';
        
        
        /* @var $repo RepoPullRequests The repository to fetch PRs */
        $repo = new RepoPullRequests ();

        
        /* @var now DateTime the actual time, for calculate active days for not closed PRs */
        $now = new DateTime ();

        
        // Normalize response
        foreach ($repo->getPullRequests ($this->owner, $this->repo) as $edge) {
            
            /* @var Item Array */
            $item = [];
            
            
            // Remove the 'node' deep in the array
            $edge = $edge['node'];
            
            
            /* @var $date1 Datetime The date when the PR was created */
            $date1 = new DateTime ($edge['createdAt']);
            
            
            /* @var $date2 Datetime The date when the PR was closed */
            $date2 = $edge['closedAt'] ? new DateTime ($edge['closedAt']) : $now;
            
            
            // Normalize fields to the avoid deeps arrays
            foreach ($edge as $key => $field) {
                if (is_array ($field)) {
                    $item[$key] = reset ($edge[$key]);
                }
            }
            
            
            // Parse the response
            $item['merged'] = $edge['merged'] == '1' ? 1 : 0;
            $item['closed'] = $edge['closed'] == '1' ? 1 : 0;
            $item['locked'] = $edge['locked'] == '1' ? 1 : 0;
            $item['days_opened'] = $date2->diff ($date1)->format ("%a");
            
            
            // Add the class
            $item['mergeable'] = $edge['mergeable'] == 'MERGEABLE' ? 1 : $item['merged'];
            
            
            // Create anchor
            $anchor = '<a target="_blank" href="' . $base_github_url . $edge['resourcePath'] . '">' . $edge['title'] . '</a>';
            
            
            // Add to the result
            $anchors[] = $anchor;
            $pull_requests[] = $item; 
            
        }
        
        
        /* @var $count_items Array counts how many positive and negative classes are */
        $count_items = array_count_values (pluck ($pull_requests, 'mergeable'));
        
        
        /* @var $amount Integer The max of balanced elements that can get  */
        $amount = min ($count_items[0], $count_items[1], $this->how_many * 1);
        
        
        // Classfy
        foreach ($pull_requests as $index => $pull_request) {
            if ($pull_request['mergeable']) {
                if (count ($positives) < $amount) {
                    $positives[] = $pull_request;
                }
                
            } else {
                if (count ($negatives) < $amount) {
                    $negatives[] = $pull_request;
                }
            }
        }
        
        
        // Merge
        $pull_requests = array_merge ($positives, $negatives);
        
        
        // Parse action
        switch ($this->action) {
            
            // Download CSV 
            case 'download':
                error_reporting (0);
                ini_set ('display_errors', 0);
                header ('Content-Type: application/csv');
                header ('Content-Disposition: attachment; filename="' . date ('YmdHis') . '-githubsa.csv";');
                
                foreach ($pull_requests as $index => $pull_request_attributes) {
                    if ($index == 1) {
                        break;
                    }
                    echo implode (';', array_keys ($pull_request_attributes)) . "\n";
                }
                
                foreach ($pull_requests as $pull_request_attributes) {
                    echo implode (';', $pull_request_attributes) . "\n";
                }
                break;
                
            case 'view':
            default:
            
                /** @var vars Array the variables to send to the template */
                $vars = [
                    'anchors'       => $anchors,
                    'pull_requests' => $pull_requests,
                    'download_link' => BASE_URL . $this->owner . '/' . $this->repo . '/download'
                ];
                
                
                // Write body
                $this->_response->getBody ()->write ($this->_template->render ('pull-requests.html', $vars));
                
                break;
        }        
        
    }
}
