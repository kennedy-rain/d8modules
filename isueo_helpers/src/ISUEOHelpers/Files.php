<?php

namespace Drupal\isueo_helpers\ISUEOHelpers;

use Drupal;
use Drupal\Core\Site\Settings;

class Files {

  // Get a file from the datastore
  public static function file_from_datastore(string $path, string $cache_key = '', int $cache_minutes = 0) : string {
    // Get credentials for the datastore
    $creds = Settings::get('datastore_creds');
    if (empty($creds)) {
      // Don't have credentials, log it, and return an empty json string;
      Drupal::logger('isueo_helpers')->warning('Need to put datastore_creds into the settings.php file!');
      return '[]';
    } else {
      $creds .= '@';
    }

    // Build the path
    if (str_starts_with($path, '/')) {
      $path = 'https://datastore.exnet.iastate.edu' . $path;
    } elseif (!str_starts_with($path, 'http')) {
      $path = 'https://datastore.exnet.iastate.edu/' . $path;
    }
    $path = str_replace('http://', 'https://', $path);

    // Insert the credentials and get file
    $path = str_replace('https://', 'https://' . $creds, $path);

    return $path;
    return Files::file_get_from_url($path, $cache_key, $cache_minutes);
  }

  // Get a file from a URL
  // TODO: Add some sort of caching mechanism
  public static function file_get_from_url($url, string $cache_key = '', int $cache_minutes = 0) {
    return file_get_contents($url);
  }

}
