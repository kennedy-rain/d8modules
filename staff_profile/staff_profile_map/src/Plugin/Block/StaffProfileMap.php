<?php

namespace Drupal\staff_profile_map\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Field\FieldFilteredMarkup;

/**
 * Provides a 'Staff Profile Map' Block.
 *
 * @Block(
 *   id = "staff_profile_map",
 *   admin_label = @Translation("Staff Profile Map block"),
 *   category = @Translation("Staff Profile Map"),
 * )
 */
class StaffProfileMap extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    //Initialize some variables
    $results = '';
    $styles = $this->default_styles();
    $mapPath = \Drupal::service('file_system')->realpath(\Drupal::service('module_handler')->getModule('staff_profile_map')->getPath()) . '/iowa_map.svg';
    $mapCode = file_get_contents($mapPath);
    $displayMap = false;

    //Make sure we're on a node
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      //Handle Counties Served
      foreach ($node->get('field_staff_profile_cty_served') as $ctyServed) {
        $county_name = $ctyServed->entity->label();
        if (!empty($county_name)) {
          $styles .= '#' . $this->fixCounty($county_name) . ' polygon {fill:pink;}' . PHP_EOL;
          $displayMap = true;
        }
      }

      //Handle Base County
      if ($node->get('field_staff_profile_base_county')->entity != null) {
        $base_county = $node->field_staff_profile_base_county->entity->label();
        if (!empty($base_county)) {
          $styles .= '#' . $this->fixCounty($base_county) . ' polygon {fill:red;}' . PHP_EOL;
          $displayMap = true;
        }
      }
    }

    //Add allowed tags for svg map
    $tags = FieldFilteredMarkup::allowedTags();
    array_push($tags, 'svg', 'g', 'polygon', 'path', 'style');

    //Add the map to results when appropriate
    if ($displayMap) {
      $results .= str_replace('/*ReplaceMe*/', $styles, $mapCode);
    }

    return [
      '#allowed_tags' => $tags,
      '#markup' => $results,
    ];
  }

  /**
   * @return int
   */
  public function getCacheMaxAge()
  {
    return 0;
  }

  private function default_styles()
  {
    $return_string = '';


    $return_string .= '/* Added for Staff Directory Fill */' . PHP_EOL;

    $return_string .= 'svg {max-width:400px;}' . PHP_EOL;
    $return_string .= 'g.RegionNumber {display:none;}' . PHP_EOL;
    $return_string .= 'g.CountyName {display:none;}' . PHP_EOL;
    $return_string .= '#Region_1 polygon, #Region_2 polygon, #Region_3 polygon, #Region_4 polygon, #Region_5 polygon, #Region_6 polygon, #Region_7 polygon, #Region_8 polygon, #Region_9 polygon, #Region_10 polygon, #Region_11 polygon, #Region_12 polygon, #Region_13 polygon, #Region_14 polygon, #Region_15 polygon, #Region_16 polygon, #Region_17 polygon, #Region_18 polygon, #Region_19 polygon, #Region_20 polygon, #Region_21 polygon, #Region_22 polygon, #Region_23 polygon, #Region_24 polygon, #Region_25 polygon, #Region_26 polygon, #Region_27 polygon {fill:#ffffff; stroke:black;}' . PHP_EOL;

    return $return_string;
  }
  private function fixCounty($county_name)
  {
    $county_name = str_replace(' ', '_', $county_name);
    $county_name = str_replace('\'', '', $county_name);
    $county_name = str_replace('Pottawattamie_-_West', 'West_Pottawattamie', $county_name);
    $county_name = str_replace('Pottawattamie_-_East', 'East_Pottawattamie', $county_name);

    return $county_name;
  }
}
