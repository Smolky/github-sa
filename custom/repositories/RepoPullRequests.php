<?php

namespace GitHubSA;

use GuzzleHttp\Client;

/**
 * RepoPullRequests
 *
 * @package GitHUB SA
 */
Class RepoPullRequests {

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
    }
    
    
    /**
     * getPullRequests
     *
     * Fetches all the pullrequest
     *
     * @param $owner String The owner of the repository
     * @param $repo String The name of the the repository
     * @param $cursor String An internal cursor for pagination purposes
     * @param $total_iterations int  A internal counter to avoid endless loops
     *
     * @package Github SA
     */
    public function getPullRequests ($owner, $repo, $cursor='', $total_iterations=0) {
        
        /* @var $results Array Temporal variable to store the results */
        $results = [];
        
        
        // Check iterations to avoid endless loops
        $total_iterations++;
        if ($total_iterations == 2) {
            return [];
        }
        
        
        /* @var $cursor_string String Append the cursor por pagination */
        $cursor_string = $cursor == '' ? '' : (' after:\"' . $cursor. '\"');
        
        
        // Prepare the query
        $query = '
            query { 
            
                repository(owner:\"' . $owner . '\", name:\"' . $repo . '\") {
                    name
                
                    pullRequests(first:25 ' . $cursor_string . ') {
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
                                
                                title 
                                
                                mergeable 
                                resourcePath
                                
                                closed
                                locked
                                
                                createdAt
                                closedAt
                                mergedAt
                                
                                lines_added: additions
                                lines_deleted: deletions
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
        ';
        
        
        // Return the response of the query
        $body = $this->run_query ($query);
        
        
        
        // Store results
        if (is_array ($body['data']['repository']['pullRequests']['edges'])) {
            $results = array_merge ($results, $body['data']['repository']['pullRequests']['edges']);
        }
        
        
        // Fetch page info
        $pageinfo = $body['data']['repository']['pullRequests']['pageInfo'];
        if ($pageinfo['hasNextPage']) {
            $results = array_merge ($results, $this->getPullRequests ($owner, $repo, $pageinfo['endCursor'], $total_iterations));
        }

        
        // Return response
        return $results;
        
        
    }
    
    
    /**
     * run_query
     *
     * Runs the query
     *
     * @param $query String
     *
     * @package Github SA
     */    
    private function run_query ($query) {
        
        // Run the query
        $response = $this->_client->request ('POST', '', ['body' => $this->parse_query ($query)]);
        
        
        // Parse the response
        return json_decode ($response->getBody(), true);
        
    }
    
    
    /**
     * parse_query
     *
     * Parses the query to remove line breaks
     *
     * @param $query String
     *
     * @package Github SA
     */
    private function parse_query ($query) {
    
        $full_query = '{ "query": "' . $query . '"}';
    
        return trim (preg_replace ('/\s\s+/', ' ', $full_query));
    }
    
}
