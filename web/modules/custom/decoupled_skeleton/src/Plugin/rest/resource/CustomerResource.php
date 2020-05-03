<?php

namespace Drupal\decoupled_skeleton\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\decoupled_skeleton\Entity\Customers;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "custome_resource",
 *   label = @Translation("Customer Resource"),
 *   uri_paths = {
 *     "canonical" = "/customers"
 *   }
 * )
 */
class CustomerResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    $nids = \Drupal::entityQuery('customers')->condition('type', 'default')->execute();
    if($nids){
      $nodes =  Customers::loadMultiple($nids);
      foreach ($nodes as $key => $value) {
        $data[] = [
          'id' => $value->id(),
          'name' => $value->getName(),
          'email' => $value->get('field_customer_email')->getString(),
          'phone' => $value->get('field_customer_phone_number')->getString()
        ];
      }
    }
    $response = new ResourceResponse($data);
    // In order to generate fresh result every time (without clearing
    // the cache), you need to invalidate the cache.
    $response->addCacheableDependency($data);
    return $response;
  }

}
