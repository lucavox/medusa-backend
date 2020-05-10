<?php

namespace Drupal\decoupled_skeleton\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\decoupled_skeleton\Entity\Customers;


/**
 * Get user details Resource
 *
 * @RestResource(
 *   id = "decoupled_form",
 *   label = @Translation("Decoupled Form Data"),
 *   uri_paths = {
 *     "canonical" = "/decoupled-form"
 *   }
 * )
 */
class DecoupledFormDataResource extends ResourceBase {

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
    $response->addCacheableDependency($data);
    return $response;
  }

  /**
   * Post request
   */
  public function post($data) {
    if (!empty($data)) {
      $customer_entity = Customers::create([
        'type' => 'default',
        'name' => $data['first_name'] . ' ' . $data['last_name'],
        'field_customer_first_name' => $data['first_name'],
        'field_customer_last_name' => $data['last_name'],
        'field_customer_phone_number' => $data['phone'],
        'field_customer_email' => $data['email'],
      ]);
      $customer_entity->save();
      if($customer_entity) {
       $message[] = [
         'status' => 200,
         'message' => 'Data is saved successfully',
       ];
      }
    }
    $response = new ResourceResponse($message);
    return $response;
  }

}
