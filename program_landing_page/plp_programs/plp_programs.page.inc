<?php
use Drupal\node\Entity\Node;
use Drupal\isueo_helpers\ISUEOHelpers;

// Return an array of existing nodes with MyData ID as the array key
function plp_programs_get_existing_nodes(string $content_type, string $mydata_id_field) {
  $nids = \Drupal::entityQuery('node')->accessCheck(false)->condition('type', $content_type)->execute();
  $tmpnodes =  Node::loadMultiple($nids);
  $nodes = [];
  foreach ($tmpnodes as $node) {
    $nodes[$node->get($mydata_id_field)->getString()] = $node;
  }

  return $nodes;
}

// Return an array of items from a JSON file
function plp_programs_get_related_items(string $json_url, string $field_name, array $programs)
{
  $my_array = [];

  // Find measurements for the programs
  $raw = ISUEOHelpers\Files::fetch_url($json_url);
  $widgets = json_decode($raw, true);
  foreach ($widgets as $widget) {
    $my_array[$widget[$field_name]][] = $widget;
  }

  return $my_array;
}

// Returns the number of item related to the program and it's childres, could be indicators, measurements, events, etc
function plp_programs_get_number_of_items(string $programid, array $items, array $children)
{
  $value = 0;

  // Add in the number of items directly associated with this program
  if (array_key_exists($programid, $items)) {
    $value = $value + count($items[$programid]);
  }

  // Also add in the items associated with the children
  if (array_key_exists($programid, $children)) {
    foreach ($children[$programid] as $child) {
      if (array_key_exists($child['Id'], $items)) {
        $value = $value + count($items[$child['Id']]);
      }
    }
  }

  return $value;
}


// Returns a string, the contents of the body field
function plp_programs_generate_body(array $program)
{
    $body = '';
    if (!empty($program['Web_Description__c'])) {
      $body = $program['Web_Description__c'];
    } elseif (!empty($program['hed__Extended_Description__c'])) {
      $body = $program['hed__Extended_Description__c'];
    } else {
      $body = $program['hed__Description__c'];
    }
    if (!empty($program['Smugmug_ID__c'])) {
      $body = '<img class="educational_program_image" src="https://photos.smugmug.com/photos/' . $program['Smugmug_ID__c'] . '/0/XL/' . $program['Smugmug_ID__c'] . '-XL.jpg" alt="" />' . $body . '<div class="clearer"></div>';
    }
    if (!empty($program['Intended_Audiences__c'])) {
      $body .= '<p><b>Intended Audiences:</b><br />' . str_replace(';', ', ', $program['Intended_Audiences__c']) . '</p>';
    }

    return $body;
}
