<?php

namespace Drupal\decoupled_skeleton\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Customers type entity.
 *
 * @ConfigEntityType(
 *   id = "customers_type",
 *   label = @Translation("Customers type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\decoupled_skeleton\CustomersTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\decoupled_skeleton\Form\CustomersTypeForm",
 *       "edit" = "Drupal\decoupled_skeleton\Form\CustomersTypeForm",
 *       "delete" = "Drupal\decoupled_skeleton\Form\CustomersTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\decoupled_skeleton\CustomersTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "customers_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "customers",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/customers_type/{customers_type}",
 *     "add-form" = "/admin/structure/customers_type/add",
 *     "edit-form" = "/admin/structure/customers_type/{customers_type}/edit",
 *     "delete-form" = "/admin/structure/customers_type/{customers_type}/delete",
 *     "collection" = "/admin/structure/customers_type"
 *   }
 * )
 */
class CustomersType extends ConfigEntityBundleBase implements CustomersTypeInterface {

  /**
   * The Customers type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Customers type label.
   *
   * @var string
   */
  protected $label;

}
