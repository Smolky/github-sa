<?php

use GuzzleHttp\Client;


/**
 * Index
 *
 * @package Core-o-Graphy
 */
class Index extends CoreOGraphy\BaseController {

    /** @var $_client GuzzleHttp\Client; */
    protected $_client;

    
    /**
     * __construct
     *
     * @package Core-o-Graphy
     */    
    public function __construct () {
    
        $this->_client = new Client ([
            'base_uri' => GITHUB_END_POINT,
            'timeout'  => 20.0,
            'headers' => ['Authorization' => 'Bearer ' . GITHUB_ACCESS_TOKEN]
        ]);
        
    
        // Delate on parent 
        parent::__construct ();
    }
    
    
    /**
     * handleRequest
     *
     * @package Core-o-Graphy
     */
    public function handleRequest () {
    
        /* @var $paginated_fields Array */
        $paginated_fields = [
            'assignees', 'comments', 'commits', 
            'labels', 'participants', 'reactions', 'thumbs_up', 
            'thumbs_down', 'laughs', 'hoorays', 'reviews_requests', 'reviews',
            'project_cards', 'favorites', 'confused'
        ];
        
    
        // Prepare the query to fetch
        // @link http://graphql.org/learn/pagination/ 
        $body = trim (preg_replace ('/\s\s+/', ' ', '{ "query": "
            query { 
                
                repository(owner:\"magento\", name:\"magento2\") {
                    name
                    
                    pullRequests(last:20) {
                        totalCount
                        edges {
                            node {
                                assignees {
                                    totalCount
                                }
                                comments {
                                    totalCount
                                }
                                commits {
                                    totalCount
                                }
                                labels {
                                    totalCount
                                }
                                participants {
                                    totalCount
                                }
                                project_cards: projectCards {
                                    totalCount
                                }
                                reactions: reactions {
                                    totalCount
                                }
                                thumbs_up: reactions(content:THUMBS_UP) {
                                    totalCount
                                }
                                thumbs_down: reactions(content:THUMBS_DOWN) {
                                    totalCount
                                }
                                laughs: reactions(content:LAUGH) {
                                    totalCount
                                }
                                hoorays: reactions(content:HOORAY) {
                                    totalCount
                                }
                                favorites: reactions(content:HEART) {
                                    totalCount
                                }
                                confused: reactions(content:CONFUSED) {
                                    totalCount
                                }
                                reviews_requests: reviewRequests {
                                    totalCount
                                }
                                reviews {
                                    totalCount
                                }
                                
                                mergeable 
                                
                                closed
                                locked
                                
                                createdAt
                                closedAt
                                mergedAt
                                
                                additions
                                changes: changedFiles
                                
                                merged
                                
                            }
                            cursor
                        }
                        pageInfo {
                            endCursor
                            hasNextPage
                        }
                    }
                }
                
            }
        "}'));

        
        // Run the query
        $response = $this->_client->request ('POST', '', ['body' => $body]);
        
        
        // Parse the response
        $body = json_decode ($response->getBody(), true);
        
        
        // Normalize response
        $body = $body['data']['repository']['pullRequests']['edges'];
        foreach ($body as & $edge) {
            
            // Normalize fields to the avoid deeps arrays
            foreach ($paginated_fields as $field) {
                $edge['node'][$field] = $edge['node'][$field]['totalCount'];
            }
            
            
            // Remove deep
            $edge = $edge['node'];
            
            
            // Normalize to 1|0
            $edge['mergeable'] = $edge['mergeable'] == 'MERGEABLE' ? 1 : 0;
            $edge['closed'] = $edge['closed'] == '1' ? 1 : 0;
            $edge['merged'] = $edge['merged'] == '1' ? 1 : 0;
            $edge['locked'] = $edge['locked'] == '1' ? 1 : 0;
            
            
            // Create new fields
            $date1 = new DateTime ($edge['createdAt']);
            $edge['days_opened'] = 0;
            if ($edge['closedAt']) {
                $date2 = new DateTime ($edge['closedAt']);
            } else {
                $date2 = new DateTime ();
            }
            
            unset ($edge['createdAt']);
            unset ($edge['closedAt']);
            unset ($edge['mergedAt']);
            
            
            $edge['days_opened'] = $date2->diff ($date1)->format("%a");
            
            
        }
        
        

        
        // Write body
        $this->_response->getBody ()->write ($this->_template->render ('pull-requests.html', ['response' => $body]));
    }
}
