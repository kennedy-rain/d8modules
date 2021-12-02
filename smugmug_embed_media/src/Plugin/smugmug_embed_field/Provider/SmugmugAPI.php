<?php

namespace Drupal\smugmug_embed_field\Plugin\smugmug_embed_field\Provider;

use Drupal\smugmug_embed_field\ProviderPluginBase;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\Plugin\ImageEffect\ScaleImageEffect;
use Drupal\image\Plugin\ImageEffect\CropImageEffect;
use Drupal\Component\Utility\HTML;
/**
 * A Smugmug provider plugin.
 * This Plugin is only intended to be used for ISUExtensionImages, 
 *
 * @ImageEmbedProvider(
 *   id = "smugmug_api",
 *   title = @Translation("Smugmug API")
 * )
 */
class SmugmugAPI extends ProviderPluginBase {

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($size, $alt = '') {
    //Assume max dimension codes for medium style unless otherwise specified
    $max_dim = 220;
    $height = 220;
    $width = 220;
    //Load image style and set to image size that is not greater
    if ($size != '') {
      $style = ImageStyle::load($size);
      foreach ($style->getEffects()->getIterator() as $styleplugin) {
        // Only look for sizes, break after first sizing 
        // Assuming only effect is scaling, as that is the only one smugmug provides
        if ($styleplugin instanceof ScaleImageEffect) {
          $width = $styleplugin->getConfiguration()['data']['width'];
          $height = $styleplugin->getConfiguration()['data']['height'];
          $max_dim = max($width, $height);
          //break;
        }
      }
    }

    //Smugmug sizes and corresponding codes, these are the larger 
    $available_sizes = [
      100 => 'Ti',//Tiny
      150 => 'Th',//Thumbnail
      300 => 'S',
      450 => 'M',
      600 => 'L',
      768 => 'XL',
      960 => 'X2',//2XL
      1200 => 'X3',
      2048 => 'X4',
      2560 => 'X5',
      3840 => '4K',
      5120 => '5K',
      PHP_INT_MAX => '',//Original image size, can be any actual dimension
    ];
    
    //Minimum size 100px, go through available sizes until one is equal to or larger than the image style scale
    $closest = 100;
    foreach ($available_sizes as $size_key => $charcode) {
      if ($size_key >= $max_dim) {
        $closest = $size_key;
        break;
      }
    }
    $size_char = $available_sizes[$closest];
    
    // Use metadata alt text if none is provided
    // Alt text is mandatory field, so this should not happen
    if ($alt == '') {
      $alt = $this->getAltText();
    }
    
    //Uses smugmug-embed-image.html.twig template
    $image = [
      '#type' => 'smugmug_embed_image',
      '#provider' => 'smugmugapi',
      '#url' => "https://photos.smugmug.com/photos/i-{$this->getImageId()}/0/{$size_char}/i-{$this->getImageId()}-{$size_char}.jpg",
      '#alt' => HTML::escape($alt), //htmlspecialchars()
      '#height' => $height,
      '#width' => $width,
      '#attributes' => [
        'frameborder' => '0',
      ],
    ];
    return $image;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    $thumbnail = $this->oEmbedData();
    if (isset($thumbnail)) {
      return $thumbnail->Response->Image->ThumbnailUrl;
    } else {
      return null;
    }
  }
  
  public function getThumbnailUri() {
    $thumbnail = $this->oEmbedData();
    if (isset($thumbnail)) {
      return $thumbnail->Response->Image->ThumbnailUrl;
    } else {
      return null;
    }
    
  }

  /**
   * Get the smugmug oembed data.
   * Smugmug appears to have deprecated OEmbed, use their API directly
   * https://api.smugmug.com/api/v2/image/<ID>?APIKey=<API Key>&_accept=application/json
   *    Image Title, Caption, Thumbnail URL, Original Height and Width
   * https://api.smugmug.com/api/v2/image/<ID>!sizes?APIKey=<API Key>&_accept=application/json
   *    Image Large, Image Medium, Image Small, ImageThumb etc sizes urls
   * Image 
   *
   * @return array
   *   An array of data from the oembed endpoint.
   */
  protected function oEmbedData() {
    $config = \Drupal::config('smugmug_embed_field.settings');
    $smugmug_api_key = $config->get('smugmug_api_key');
    if ($smugmug_api_key != '') {
      $data = file_get_contents('https://api.smugmug.com/api/v2/image/' . $this->getIdFromInput($this->getInput()) . '?APIKey=' . $smugmug_api_key . '&_accept=application/json');
      return json_decode($data);
    } else {
      return null;
    }
    
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {
    preg_match('/^https?:\/\/(isuextensionimages\.)?smugmug.com\/[A-Za-z0-9\-\_\/]+i\-(?<id>[A-Za-z0-9]*)(\/A)?$/', $input, $matches);
    return isset($matches['id']) ? $matches['id'] : FALSE;
  }


  /**
   * {@inheritdoc}
   */
  public function getName() {
    $meta = $this->oEmbedData();
    if (isset($meta) && $meta->Response->Image->Title != '') {
      return $meta->Response->Image->Title;
    } else if (isset($meta)) {
      return $meta->Response->Image->FileName;
    } else {
      return "SmugMug Image: {$this->getImageId()}";
    }
  }
  
  /**
   * {@inheritdoc}
   */
  public function getAltText() {
    $meta = $this->oEmbedData();
    if ($meta->Response->Image->Caption != '') {
      return $meta->Response->Image->Caption;
    } elseif ($meta->Response->Image->Title != '') {
      return $meta->Response->Image->Title;
    } else {
      return $meta->Response->Image->FileName;
    }
  }

}
