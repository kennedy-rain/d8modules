<?php

namespace Drupal\smugmug_embed_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A widget to input video URLs.
 *
 * @FieldWidget(
 *   id = "smugmug_embed_field_textfield",
 *   label = @Translation("Smugmug Textfield"),
 *   field_types = {
 *     "smugmug_embed_field"
 *   },
 * )
 */
class ImageTextfield extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    //$element = parent::formElement($items, $delta, $element, $form, $form_state);
    
    $element['value'] = $element + [
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#size' => 60,
      '#maxlength' => $this->getFieldSetting('max_length'),
      '#attributes' => ['class' => ['js-text-full', 'text-full']],
      '#allowed_providers' => $this->getFieldSetting('allowed_providers'),
      '#required' => TRUE
    ];
    
    $element['alt'] = [
      '#title' => t('Alternative text'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->alt) ? $items[$delta]->alt : NULL,
      '#description' => t('Short description of the image used by screen readers and displayed when the image is not loaded. This is important for accessibility.'),
      '#maxlength' => 255,
      '#required' => TRUE
    ];
    return $element;
  }
  

}
