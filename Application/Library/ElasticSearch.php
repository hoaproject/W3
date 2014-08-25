<?php

namespace Application\Library {

class ElasticSearch {

    const HOST  = '188.165.240.124:9200';
    const INDEX = 'hoa-project';
    const TYPE  = 'page';

    protected $client;

    public function __construct( ) {

        $this->client = new \Elasticsearch\Client(array('hosts' => array(self::HOST)));
    }

    public function addPage( $page, $id ) {

        $document = array();
        $document['id']    = $id;
        $document['index'] = self::INDEX;
        $document['type']  = self::TYPE;
        $document['body']  = $page;

        $this->client->index($document);
    }

    public function search( $query ) {

        $results = array();

        $params = array();
        $params['index'] = self::INDEX;
        $params['type']  = self::TYPE;
        $params['body']['query'] = $query;

        $ESresults = $this->client->search($params);
        if(!empty($ESresults['hits']['hits'])) {
            foreach ($ESresults['hits']['hits'] as $result) {
                $results[] = $result['_source'];
            }
        }

        return $results;
    }
}

}