<?php

namespace Drupal\decoupled_skeleton;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\decoupled_skeleton\Entity\CustomersInterface;

/**
 * Defines the storage handler class for Customers entities.
 *
 * This extends the base storage class, adding required special handling for
 * Customers entities.
 *
 * @ingroup decoupled_skeleton
 */
class CustomersStorage extends SqlContentEntityStorage implements CustomersStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(CustomersInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {customers_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {customers_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(CustomersInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {customers_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('customers_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
