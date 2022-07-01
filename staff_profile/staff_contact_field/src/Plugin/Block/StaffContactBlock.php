<?php

namespace Drupal\staff_contact_field\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Field\FieldFilteredMarkup;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Staff Contact' Block.
 *
 * @Block(
 *   id = "staff_contact_field",
 *   admin_label = @Translation("Staff Contact Block"),
 *   category = @Translation("Staff Contact "),
 * )
 */
class StaffContactBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    global $base_url;
    //Initialize some variables
    $results = '';

    // Get the Staff Profiles from this site
    $nids = \Drupal::entityQuery('node')->condition('type', 'staff_profile')->condition('status', 1)->sort('field_staff_profile_last_name')->sort('field_staff_profile_first_name')->execute();
    $staff_nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      $fields = $node->getFields();
      foreach ($fields as $field) {
        if ($field->getFieldDefinition()->getType() == 'staff_contact_field') {
          $contacts = unserialize($field->getValue()[0]['contacts']);
          arsort($contacts);
          $results = '<ul class="staff_contact_list">' . PHP_EOL;

          foreach ($contacts as $key => $value) {
            foreach ($staff_nodes as $staff_member) {
              if ($staff_member->id() == $key) {
                $results .= '<li><a href="' . $base_url . \Drupal::service('path_alias.manager')->getAliasByPath('/node/'. $staff_member->id()) . '">' . $staff_member->getTitle() . '</a></li>' . PHP_EOL;
              }
            }
          }
          $results .= '</ul>' . PHP_EOL;
        }
      }
    }

    return [
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
}
