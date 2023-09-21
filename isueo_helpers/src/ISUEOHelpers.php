<?php

namespace Drupal\isueo_helpers;
//use Drupal\Core\Controller\ControllerBase;

class ISUEOHelpers {

  // Return a map as an svg file, after applying any given styles
  public static function map_get_svg($styles = '')  {
    $mapPath = \Drupal::service('file_system')->realpath(\Drupal::service('module_handler')->getModule('isueo_helpers')->getPath()) . '/images/iowa_map.svg';
    $mapCode = file_get_contents($mapPath);

    return str_replace('/*ReplaceMe*/', $styles, $mapCode);
  }

}
