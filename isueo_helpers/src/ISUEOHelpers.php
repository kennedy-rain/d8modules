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

  // Return a color code from an array
  public static function map_get_colorcode($count, $colors = []) {
    // Set up some initial values for variables
    if (empty($colors)) {
      $colors = ['#c8102e', '#7c2529', '#4a4a4a', '#f5f5f5', '#ebebeb', '#008540', ];
      //$colors = ['#c8102e', '#7c2529', '#c84a4a', '#f5c8f5', '#ebebc8', '#008540', ];
    }
    $number_of_colors = count($colors);

    return $colors[$count % $number_of_colors];
  }

}
