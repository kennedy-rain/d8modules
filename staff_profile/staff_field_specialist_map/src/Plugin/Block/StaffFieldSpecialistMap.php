<?php

namespace Drupal\staff_field_specialist_map\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Field\FieldFilteredMarkup;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Entity\View;

/**
 * Provides a 'Staff Field Specialist Map' Block.
 *
 * @Block(
 *   id = "staff_field_specialist_map",
 *   admin_label = @Translation("Staff Field Specialist Map block"),
 *   category = @Translation("Staff Field Specialist Map"),
 * )
 */
class StaffFieldSpecialistMap extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $results = '';

    //$blah = View::load('staff_directory')->
    //$results = json_encode($blah);
    // See: https://pixelthis.gr/content/drupal-9-gettings-views-custom-block-di-using-drupal-core-render-class
    $blah = \Drupal\views\Views::getView('staff_directory');
    $blah->setDisplay('block_2');
    $blah->execute();
    $view_result = $blah->result;
    $field_specialists = [];

    foreach ($view_result as $staff) {
      $field_specialists[] = intval($staff->nid);
      $results .= '<br />' . $staff->nid;
    }
    $results .= '<br />' . json_encode($field_specialists);

    $nodes = Drupal\node\Entity\Node::loadMultiple($field_specialists);

    $results .= '<br />' . count($nodes);

    //Add allowed tags for svg map
    $tags = FieldFilteredMarkup::allowedTags();
    array_push($tags, 'svg', 'g', 'polygon', 'path', 'style');


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

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state)
  {
    $config = $this->getConfiguration();

    $form['max_size'] = array(
      '#type' => 'textfield',
      '#title' => t('Maximum Width of Map, in pixels'),
      //'#description' => t('Zero (0) means display all events'),
      '#size' => 15,
      '#default_value' => $config['max_size'],
    );

    $form['base_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Color to shade the Base County'),
      //'#description' => t('Zero (0) means display all events'),
      '#size' => 15,
      '#default_value' => $config['base_color'],
    );

    $form['served_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Color to shade the Counties Served'),
      //'#description' => t('Zero (0) means display all events'),
      '#size' => 15,
      '#default_value' => $config['served_color'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();

    $this->configuration['max_size'] = $values['max_size'];
    $this->configuration['base_color'] = $values['base_color'];
    $this->configuration['served_color'] = $values['served_color'];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return array(
      'max_size' => '400',
      'base_color' => '#CC0000',
      'served_color' => '#F1BE48',

    );
  }
}
