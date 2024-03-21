<?php

namespace Drupal\isueo_helpers\ISUEOHelpers;

use Drupal;
use Exception;
use Symfony\Component\HttpClient\HttplugClient;
use Typesense\Client;

class Typesense
{
  // Get the Client for our Typesense search server
  public static function getClient()
  {
    $client = new Client(
      [
        'api_key' => 'O1tfLS2ZsKlYlDpLq16ZYaiB2m2doa9o',
        'nodes' => [
          [
            'host' => 'typesense.exnet.iastate.edu',
            'port' => '8108',
            'protocol' => 'https',
          ],
        ],
        'client' => new HttplugClient(),
      ]
    );
    return $client;
  }

  public static function searchCollection(string $collection, string $q = '*', string $query_by = '*', string $sort_by = '', int $per_page = 10, int $page = 1, string $filter_by = '', bool $exhaustive_search = true)
  {
    try {
    $client = self::getClient();
    if ($client) {
    $query_array = [
    'q' => $q,
    'query_by' => $query_by,
    'sort_by' => $sort_by,
    'per_page' => $per_page,
    'page' => $page,
    'filter_by' => $filter_by,
    'exhaustive_search' => $exhaustive_search,
    ];
    return($client->collections[$collection]->getDocuments()->search($query_array));
    }
    } catch (Exception $e) {
      Drupal::logger('isueo_helpers')->info('Error in searchCollection: ' . $e->getMessage());
    }
    return (null);
  }
}
