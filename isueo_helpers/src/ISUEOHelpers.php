<?php

namespace Drupal\isueo_helpers;
//use Drupal\Core\Controller\ControllerBase;

class ISUEOHelpers {

  // Return a map as an svg file, after applying any given styles
  public static function map_get_svg($styles = '', $links = [])  {
    $mapPath = \Drupal::service('file_system')->realpath(\Drupal::service('module_handler')->getModule('isueo_helpers')->getPath()) . '/images/iowa_map.svg';
    $mapCode = file_get_contents($mapPath);
    $mapCode = str_replace('/*CustomStyles*/', $styles, $mapCode);

    foreach ($links as $key => $value) {
      $mapCode = str_replace('/*' . $key .' link*/', '<a xlink:href="' . $value . '">', $mapCode);
      $mapCode = str_replace('/*' . $key .' endlink*/', '</a>', $mapCode);
    }

    return $mapCode;
  }

}
