<?php

namespace Drupal\decoupled_skeleton\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\decoupled_skeleton\Entity\Customers;
use Drupal\Core\Form\FormState;

/**
 * Decoupled form Resource
 *
 * @RestResource(
 *   id = "decoupled_form_element_resource",
 *   label = @Translation("Get Decoupled Form Elements"),
 *   uri_paths = {
 *     "canonical" = "/decoupled-form-elements"
 *   }
 * )
 */
class DecoupledFormResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $form_state = new FormState();
    $form_state->setRebuild();
    $form_attributes = \Drupal::formBuilder()->buildForm('Drupal\decoupled_skeleton\Form\UserDetailsForm', $form_state);
    foreach ($form_attributes as $key => $form_attribute) {
      if (isset($form_attribute['#type']) && !in_array($form_attribute['#type'], ['hidden', 'token'])) {
        $form_elements[$key] = $form_attribute;
      }
    }
    $response = new ResourceResponse($form_elements);
    // In order to generate fresh result every time (without clearing
    // the cache), you need to invalidate the cache.
    $response->addCacheableDependency($form_elements);
    return $response;
  }

}
