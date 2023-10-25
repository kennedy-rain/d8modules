<?php

namespace Drupal\social_red\Controller;

use Drupal\Core\Controller\ControllerBase;


class socialRedTemplate extends ControllerBase {

    public function socialRedTemplate() {

        return [
            '#theme' => 'block__social_media_red_bar',
            '#version' => 'Drupal 8+',
        ];

    }

}