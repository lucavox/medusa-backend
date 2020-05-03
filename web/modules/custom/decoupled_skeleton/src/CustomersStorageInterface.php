<?php

namespace Drupal\decoupled_skeleton;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface CustomersStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Customers revision IDs for a specific Customers.
   *
   * @param \Drupal\decoupled_skeleton\Entity\CustomersInterface $entity
   *   The Customers entity.
   *
   * @return int[]
   *   Customers revision IDs (in ascending order).
   */
  public function revisionIds(CustomersInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Customers author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Customers revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\decoupled_skeleton\Entity\CustomersInterface $entity
   *   The Customers entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(CustomersInterface $entity);

  /**
   * Unsets the language for all Customers with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
