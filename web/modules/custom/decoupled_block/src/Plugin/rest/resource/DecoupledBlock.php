<?php

namespace Drupal\decoupled_block\Plugin\rest\resource;

use Drupal\Component\Serialization\Json;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "decoupled_block",
 *   label = @Translation("Decoupled block"),
 *   uri_paths = {
 *     "canonical" = "/decoupled_block/block/{id}"
 *   }
 * )
 */
class DecoupledBlock extends ResourceBase {

  /**
   * Block.
   *
   * @param $id
   * @return JsonResponse Return JSON string.
   *   Return JSON string.
   */
  public function get($id) {
    if (\Drupal::currentUser()->isAnonymous()) {
      throw new AccessDeniedHttpException();
    } elseif (!\Drupal::currentUser()->hasPermission('view all revisions')) {
      throw new AccessDeniedHttpException();
    } elseif (!\Drupal::currentUser()->getRoles('administrator')) {
      throw new AccessDeniedHttpException();
    }
    $ids = \Drupal::entityQuery('block')
      ->condition('plugin', $id)
      ->execute();
    foreach ($ids as $id) {
      $block = \Drupal\block\Entity\Block::load($id)->toArray();
    }
    $jsonBlock = Json::encode($block);
    return JsonResponse::fromJsonString($jsonBlock, '200');
  }

}
