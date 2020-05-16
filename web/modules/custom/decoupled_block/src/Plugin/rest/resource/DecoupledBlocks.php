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
 *   id = "decoupled_blocks",
 *   label = @Translation("Decoupled blocks"),
 *   uri_paths = {
 *     "canonical" = "/decoupled_block/blocks"
 *   }
 * )
 */
class DecoupledBlocks extends ResourceBase {

  /**
   * Blocks on page.
   */
  public function get() {
    if (\Drupal::currentUser()->isAnonymous()) {
      throw new AccessDeniedHttpException();
    } elseif (!\Drupal::currentUser()->hasPermission('view all revisions')) {
      throw new AccessDeniedHttpException();
    } elseif (!\Drupal::currentUser()->getRoles('administrator')) {
      throw new AccessDeniedHttpException();
    }

    $path = $_GET['path'];
    $nids = \Drupal::entityQuery('block')->execute();
    $nodes =  \Drupal\block\Entity\Block::loadMultiple($nids);
    $blocksPage = [];
    foreach ($nodes as $block) {
      $page = $block->getVisibility()['request_path']['pages'];
      $access = $block->getVisibility()['request_path']['negate'];
      if (($page == $path) && ($access != true)) {
        $blocksPage[] = $block->toArray();
      }
    }

    $jsonBlocks = Json::encode($blocksPage);
    return JsonResponse::fromJsonString($jsonBlocks, '200');
  }

}
