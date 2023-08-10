<?php

namespace Drupal\county_jobs_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Field\FieldFilteredMarkup;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Jobs Block from a feed
 *
 * @Block(
 *   id = "county_jobs_block",
 *   admin_label = @Translation("County Jobs"),
 *   category = @Translation("Job Openings"),
 * )
 */
class CountyJobsBlock extends BlockBase
{
  const FEED_URL = 'https://www.extension.iastate.edu/jobs/feeds/county_jobs';

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    //Initialize some variables
    $config = $this->getConfiguration();
    $feed_url = !empty($config['feed_url']) ? $config['feed_url'] : self::FEED_URL;
    $feed_url = str_replace(' ', '+', $feed_url);
    $results = '';

    $openings = json_decode(file_get_contents($feed_url), true);

    $results .= '<ul class="job-links">' . PHP_EOL;
    $results .= '  <li><a href="https://www.extension.iastate.edu/jobs/">County Job Openings</a></li>' . PHP_EOL;
    $results .= '  <li><a href="https://www.jobs.iastate.edu">ISU Job Openings</a></li>' . PHP_EOL;
    $results .= '  <li><a href="https://www.extension.iastate.edu/diversity/">Diversity and Civil Rights</a></li>' . PHP_EOL;
    $results .= '</ul>' . PHP_EOL;

    if (empty($openings)) {
      $results .= '<p>No job openings at this time</p>' . PHP_EOL;
    } else {
      $results .= PHP_EOL . '<ul class="county-job-openings">' . PHP_EOL;
      foreach ($openings as $opening) {
        $results .= '  <li>' . PHP_EOL;
        $results .= '    <a href="' . $opening['view_node'] . '">' . trim($opening['title']) . '</a><br />' . PHP_EOL;
        $results .= '    ' . $opening['field_town_city'] . ', IA<br />' . PHP_EOL;
        $results .= '    ' . $opening['field_base_county'] . ' County Extension Office<br />' . PHP_EOL;
        $results .= '    <em>Application Deadline:</em> ' . $opening['field_application_deadline_date'] .
          ($opening['field_open_until_filled']  === 'True' ? ' This position will remain open until filled' : '') .
          '<br />' . PHP_EOL;
        $results .= '  </li>' . PHP_EOL;
      }
      $results .= '</ul>' . PHP_EOL;
    }


    return [
//      '#allowed_tags' => $tags,
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

    $form['feed_url'] = array(
      '#type' => 'textfield',
      '#title' => t('URL of Jobs Feed'),
      //'#description' => t('Zero (0) means display all events'),
      '#size' => 120,
      '#default_value' => !empty($config['feed_url']) ? $config['feed_url'] : self::FEED_URL,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();

    $this->configuration['feed_url'] = $values['feed_url'];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    /*
    return array(
      'max_size' => '400',
      'base_color' => '#CC0000',
      'served_color' => '#F1BE48',

    );
    */
  }

  private function fixCounty($county_name)
  {
    /*
    $county_name = str_replace(' ', '_', $county_name);
    $county_name = str_replace('\'', '', $county_name);
    $county_name = str_replace('Pottawattamie_-_West', 'West_Pottawattamie', $county_name);
    $county_name = str_replace('Pottawattamie_-_East', 'East_Pottawattamie', $county_name);

    return $county_name;
    */
  }
}

