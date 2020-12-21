<?php

/**
 * @file
 * Contains \Drupal\program_offering_blocks\Plugin\Derivative\ProgramOfferingBlocks.
 */

namespace Drupal\program_offering_blocks\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

class ProgramOfferingBlocks extends DeriverBase
{
  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition)
  {
    $config = \Drupal::config('program_offering_blocks.settings');
    $number_of_blocks = $config->get('number_of_blocks');

    for ($i = 1; $i <= $number_of_blocks; $i++) {
      $this->derivatives[$i] = $base_plugin_definition;
      $this->derivatives[$i]['admin_label'] = t('Program Offering Block: ') . $i;
    }

    return $this->derivatives;
  }
}
