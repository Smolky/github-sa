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
    
    
    /* @var $user String */
    protected $user = '';
    
    
    /**
     * handleRequest
     *
     * @package GitHub SA
     */    
    public function __construct ($owner, $user) {
    
        // Store private vars
        $this->owner = $owner;
        $this->user = $user;
    
    
        // Delegate on parent
        parent::__construct ();
    }
    
    /**
     * handleRequest
     *
     * @package GitHub SA
     */
    public function handleRequest () {
    
        // Get repo
        $repo = new RepoPullRequests ();

        
        // Parse the response
        $body = $repo->getPullRequests ($this->owner, $this->user);

        
        // Normalize response
        foreach ($body as & $edge) {
            
            // Normalize fields to the avoid deeps arrays
            foreach ($edge['node'] as $key => $field) {
                if (is_array ($field)) {
                    $edge['node'][$key] = reset ($edge['node'][$key]);
                }
            }
            
            
            // Remove a deep level
            $edge = $edge['node'];
            
            
            // Normalize to 1|0
            $edge['mergeable'] = $edge['mergeable'] == 'MERGEABLE' ? 1 : 0;
            $edge['closed'] = $edge['closed'] == '1' ? 1 : 0;
            $edge['merged'] = $edge['merged'] == '1' ? 1 : 0;
            $edge['locked'] = $edge['locked'] == '1' ? 1 : 0;
            
            
            // Create new fields about time
            $date1 = new DateTime ($edge['createdAt']);
            $edge['days_opened'] = 0;
            if ($edge['closedAt']) {
                $date2 = new DateTime ($edge['closedAt']);
            } else {
                $date2 = new DateTime ();
            }
            
            
            // Get the correct ID
            unset ($edge['createdAt']);
            unset ($edge['closedAt']);
            unset ($edge['mergedAt']);
            
            
            $edge['days_opened'] = $date2->diff ($date1)->format("%a");
            
            
        }
        


        
        // Write body
        $this->_response->getBody ()->write ($this->_template->render ('pull-requests.html', ['response' => $body]));
    }
}
