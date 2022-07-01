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

    // Get the corrent node
    $node = \Drupal::routeMatch()->getParameter('node');

    // Make sure it is a node
    if ($node instanceof \Drupal\node\NodeInterface) {
      // Step through the fields of the node
      $fields = $node->getFields();
      foreach ($fields as $field) {
        // Looking for staff_contact_field type(s)
        if ($field->getFieldDefinition()->getType() == 'staff_contact_field') {
          // Handle multiple values for one field
          foreach ($field->getValue() as $conact_list) {
            // Set up some html elements
            $results .= '<div class="contact_group">' . PHP_EOL;
            $contacts = unserialize($conact_list['contacts']);
            arsort($contacts);
            $results .= '<ul class="staff_contact_list">' . PHP_EOL;

            foreach ($contacts as $key => $value) {
              // Find the right staff member and output...
              foreach ($staff_nodes as $staff_member) {
                if ($staff_member->id() == $key) {
                  $results .= '<li><a href="' . $base_url . \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $staff_member->id()) . '">' . $staff_member->getTitle() . '</a></li>' . PHP_EOL;
                }
              }
            }
             // close out html elements
            $results .= '</ul>' . PHP_EOL;
            $results .= '</div>' . PHP_EOL;
          }
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
