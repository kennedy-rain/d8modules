<?php
namespace Drupal\smugmug_embed_field\Form;

use Drupal\media_library\Form\AddFormBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\smugmug_embed_field\Plugin\media\Source\SmugmugEmbedField;
use Drupal\media_library\MediaLibraryUiBuilder;
use Drupal\media_library\OpenerResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates a form to create media entities from Smugmug URLs.
 *
 */
class SmugmugMediaLibraryForm extends FormBase {
  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#prefix'] = '<div id="media-library-add-form-wrapper">';
    $form['#suffix'] = '</div>';
    $form['smugmug'] = [
      '#type' => 'textfield'
    ];
    
    return $form;
  }
  
  // /**
  //  * Constructs a new SmugmugMediaLibraryForm.
  //  *
  //  * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
  //  *   The entity type manager.
  //  * @param \Drupal\media_library\MediaLibraryUiBuilder $library_ui_builder
  //  *   The media library UI builder.
  //  * @param \Drupal\media_library\OpenerResolverInterface $opener_resolver
  //  *   The opener resolver.
  //  */
  // public function __construct(EntityTypeManagerInterface $entity_type_manager, MediaLibraryUiBuilder $library_ui_builder, OpenerResolverInterface $opener_resolver = NULL) {
  //   parent::__construct($entity_type_manager, $library_ui_builder, $opener_resolver);
  //   // $this->urlResolver = $url_resolver;
  //   // $this->resourceFetcher = $resource_fetcher;
  // }
  // 
  // /**
  //  * {@inheritdoc}
  //  */
  // public static function create(ContainerInterface $container) {
  //   return new static(
  //     $container->get('entity_type.manager'),
  //   );
  // }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    //return $this->getBaseFormId() . '_smugmug';
    \Drupal::logger('smugmug_embed_field')->notice("Getting Form ID");
    return 'media_library_add_form_smugmug';
  }
  
  // /**
  //  * {@inheritdoc}
  //  */
  // protected function getMediaType(FormStateInterface $form_state) {
  //   if ($this->mediaType) {
  //     return $this->mediaType;
  //   }
  // 
  //   $media_type = parent::getMediaType($form_state);
  //   if (!$media_type->getSource() instanceof SmugmugEmbedField) {
  //     throw new \InvalidArgumentException('Can only add media types which use an SmugmugEmbedField.');
  //   }
  //   return $media_type;
  // }
  // 
  // /**
  //  * {@inheritdoc}
  //  */
  // protected function buildInputElement(array $form, FormStateInterface $form_state) {
  //   $media_type = $this->getMediaType($form_state);
  //   $providers = $media_type->getSource()->getProviders();
  // 
  //   // Add a container to group the input elements for styling purposes.
  //   $form['container'] = [
  //     '#type' => 'container',
  //   ];
  // 
  //   $form['container']['url'] = [
  //     '#type' => 'url',
  //     '#title' => $this->t('Add @type via URL', [
  //       '@type' => $this->getMediaType($form_state)->label(),
  //     ]),
  //     '#description' => $this->t('Allowed providers: @providers.', [
  //       '@providers' => implode(', ', $providers),
  //     ]),
  //     '#required' => TRUE,
  //     '#attributes' => [
  //       'placeholder' => 'https://',
  //     ],
  //   ];
  // 
  //   $form['container']['submit'] = [
  //     '#type' => 'submit',
  //     '#value' => $this->t('Add'),
  //     '#button_type' => 'primary',
  //     '#validate' => ['::validateUrl'],
  //     '#submit' => ['::addButtonSubmit'],
  //     // @todo Move validation in https://www.drupal.org/node/2988215
  //     '#ajax' => [
  //       'callback' => '::updateFormCallback',
  //       'wrapper' => 'media-library-wrapper',
  //       // Add a fixed URL to post the form since AJAX forms are automatically
  //       // posted to <current> instead of $form['#action'].
  //       // @todo Remove when https://www.drupal.org/project/drupal/issues/2504115
  //       //   is fixed.
  //       'url' => Url::fromRoute('media_library.ui'),
  //       'options' => [
  //         'query' => $this->getMediaLibraryState($form_state)->all() + [
  //           FormBuilderInterface::AJAX_FORM_REQUEST => TRUE,
  //         ],
  //       ],
  //     ],
  //   ];
  //   return $form;
  // }
  // 
  // /**
  //  * Validates the Smugmug URL.
  //  *
  //  * @param array $form
  //  *   The complete form.
  //  * @param \Drupal\Core\Form\FormStateInterface $form_state
  //  *   The current form state.
  //  */
  // public function validateUrl(array &$form, FormStateInterface $form_state) {
  //   $url = $form_state->getValue('url');
  //   if ($url) {
  //     try {
  //       $resource_url = $this->urlResolver->getResourceUrl($url);
  //       $this->resourceFetcher->fetchResource($resource_url);
  //     }
  //     catch (ResourceException $e) {
  //       $form_state->setErrorByName('url', $e->getMessage());
  //     }
  //   }
  // }
  // 
  // /**
  //  * Submit handler for the add button.
  //  *
  //  * @param array $form
  //  *   The form render array.
  //  * @param \Drupal\Core\Form\FormStateInterface $form_state
  //  *   The form state.
  //  */
  // public function addButtonSubmit(array $form, FormStateInterface $form_state) {
  //   $this->processInputValues([$form_state->getValue('url')], $form, $form_state);
  // }

}
