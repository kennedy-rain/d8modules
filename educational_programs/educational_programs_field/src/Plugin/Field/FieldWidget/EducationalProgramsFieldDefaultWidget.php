<?php

/**
 * @file
 * Contains \Drupal\educational_programs_field\Plugin\Field\FieldWidget\SnippetsDefaultWidget.
 */

namespace Drupal\educational_programs_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'educational_programs_field_default' widget.
 *
 * @FieldWidget(
 *   id = "educational_programs_field_default",
 *   label = @Translation("Educational Programs Field default"),
 *   field_types = {
 *     "educational_programs_field"
 *   }
 * )
 */

class EducationalProgramsFieldDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['term_id'] = array(
      '#title' => $this->t('Select an Educational Program'),
      '#type' => 'textfield',
      '#maxlength' => 255,
      '#default_value' => isset($items[$delta]->term_id) ? $items[$delta]->term_id : NULL,
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   *
   * This function is necessary because our fields are in a container, and also to handle the ckeditor (text_format) field
   * Adapted from https://drupal.stackexchange.com/questions/246523/values-dont-get-saved-to-the-database-when-fields-are-wrapped-in-a-container
   *
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // Step through each instance of the field, in case there is more than 1
    for ($i=0; $i < count($values); ++$i) {
      // Handle the url (textfield), basically move the value up one level, without the extra container array
      if (isset($values[$i]['term_id'])) {
        $values[$i]['term_id'] = $values[$i]['term_id'];
      }
    }
    return $values;
  }

}
