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
            'timeout'  => 2.0,
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
    
        // Prepare the query to fetch
        $body = '{ "query": "
            query { 
                
                repository(owner:\"magento\", name:\"magento2\") {
                    name
                    
                    pullRequests(last:20) {
                        edges {
                            node {
                                author {
                                    login
                                }
                                closed
                                merged
                            }
                        }
                    }
                    
                }
                
            }
        "}';
        $body = trim (preg_replace ('/\s\s+/', ' ', $body));
        
        // Run the query
        $response = $this->_client->request ('POST', '', ['body' => $body]);
        
        
        // Parse the response
        $body = json_decode ($response->getBody(), true);
        

        // Write body
        $this->_response->getBody ()->write ($this->_template->render ('pull-requests.html', ['response' => $body]));
    }
}
