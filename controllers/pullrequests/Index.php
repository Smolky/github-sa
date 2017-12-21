<?php

use GitHubSA\RepoPullRequests;


/**
 * Index
 *
 * @package GitHub SA
 */
class Index extends CoreOGraphy\BaseController {
    
    /* @var $owner String */
    protected $owner = '';
    
    
    /* @var $repo String */
    protected $repo = '';
    
    
    /**
     * handleRequest
     *
     * @package GitHub SA
     */    
    public function __construct ($owner, $repo) {
    
        // Store private vars
        $this->owner = $owner;
        $this->repo = $repo;
    
    
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
        
        
        /* @var $base_github_url String */
        $base_github_url = 'https://github.com/';
        
        
        // Get repo
        $repo = new RepoPullRequests ();

        
        // Init date to now
        $now = new DateTime ();

        
        // Normalize response
        foreach ($repo->getPullRequests ($this->owner, $this->repo) as $edge) {
            
            // Shortcut
            $edge = $edge['node'];
            
            
            // Create new fields about time
            $date1 = new DateTime ($edge['createdAt']);
            if ($edge['closedAt']) {
                $date2 = new DateTime ($edge['closedAt']);
            } else {
                $date2 = $now;
            }
            
            
            
            // Parse the response
            $item = [
                'mergeable' => $edge['mergeable'] == 'MERGEABLE' ? 1 : 0,
                'closed' => $edge['closed'] == '1' ? 1 : 0,
                'merged'=> $edge['merged'] == '1' ? 1 : 0,
                'locked' => $edge['locked'] == '1' ? 1 : 0,
                'days_opened' => $date2->diff ($date1)->format("%a")
            ];
            
            // Normalize fields to the avoid deeps arrays
            foreach ($edge as $key => $field) {
                if (is_array ($field)) {
                    $item[$key] = reset ($edge[$key]);
                }
            }
            
            
            $pull_requests['<a target="_blank" href="' . $base_github_url . $edge['resourcePath'] . '">' . $edge['title'] . '</a>'] = $item; 
            
        }

        
        // Write body
        $this->_response->getBody ()->write ($this->_template->render ('pull-requests.html', ['pull_requests' => $pull_requests]));
    }
}
