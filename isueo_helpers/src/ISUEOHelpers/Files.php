<?php

namespace Drupal\isueo_helpers\ISUEOHelpers;

use Drupal;
use Drupal\Core\Site\Settings;

class Files
{

  // Get a file from a URL
  // TODO: Add some sort of caching mechanism
  public static function fetch_url(string $url, bool $ok_to_cache = false)
  {
    $creds = '';
    $url = str_replace('http://', 'https://', $url);

    if (str_starts_with(strtolower($url), 'https://datastore')) {
      // Get credentials for the datastore
      $creds = Settings::get('datastore_creds');
      if (empty($creds)) {
        // Don't have credentials, log it, and return an empty json string;
        Drupal::logger('isueo_helpers')->warning('Need to put datastore_creds into the settings.php file!');
        return '[]';
      } else {
        $creds .= '@';
      }

      // Insert the credentials and get file
      $url = str_replace('https://', 'https://' . $creds, $url);
    }

    return file_get_contents($url);
  }
}
