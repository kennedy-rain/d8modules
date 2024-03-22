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
    $config = \Drupal::config('isueo_helpers.settings');
    if ($config == null || empty($config->get('typesense.api_key'))) {
      Drupal::logger('isueo_helpers')->alert('Please enter a Typesense API Key');
    }

    $number_of_blocks = $config->get('number_of_blocks');
    $client = new Client(
      [
        'api_key' => $config->get('typesense.api_key'),
        'nodes' => [
          [
            'host' => $config->get('typesense.host'),
            'port' => $config->get('typesense.port'),
            'protocol' => $config->get('typesense.protocol'),
          ],
        ],
        'client' => new HttplugClient(),
      ]
    );
    return $client;
  }

  public static function searchCollection(string $collection, string $q = '*', string $query_by = '*', string $sort_by = '', int $per_page = 10, int $page = 1, string $filter_by = '', bool $exhaustive_search = false)
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
