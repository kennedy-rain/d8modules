<?php

namespace Drupal\smugmug_embed_field\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Validation constraint for the smugmug embed field.
 *
 * @Constraint(
 *   id = "SmugmugEmbedValidation",
 *   label = @Translation("SmugmugEmbed provider constraint", context = "Validation"),
 * )
 */
class ImageEmbedConstraint extends Constraint {

  /**
   * Message shown when a video provider is not found.
   *
   * @var string
   */
  public $message = 'Could not find a image provider to handle the given URL.';

}
