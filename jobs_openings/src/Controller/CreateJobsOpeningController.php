<?php
namespace Drupal\jobs_openings\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class CreateJobsOpeningController extends ControllerBase {
  
  //Display Form
  function view_page() {
    \Drupal::service('page_cache_kill_switch')->trigger();
    $form_class = '\Drupal\jobs_openings\Form\CreateJobsOpeningForm';
    $build['form'] = \Drupal::formBuilder()->getForm($form_class);
    
    return $build;
  }
}
