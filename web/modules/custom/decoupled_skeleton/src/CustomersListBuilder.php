<?php

namespace Drupal\decoupled_skeleton;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Customers entities.
 *
 * @ingroup decoupled_skeleton
 */
class CustomersListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Customers ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\decoupled_skeleton\Entity\Customers $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.customers.edit_form',
      ['customers' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
