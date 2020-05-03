<?php

namespace Drupal\decoupled_skeleton\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\decoupled_skeleton\Entity\CustomersInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CustomersController.
 *
 *  Returns responses for Customers routes.
 */
class CustomersController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Customers revision.
   *
   * @param int $customers_revision
   *   The Customers revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($customers_revision) {
    $customers = $this->entityTypeManager()->getStorage('customers')
      ->loadRevision($customers_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('customers');

    return $view_builder->view($customers);
  }

  /**
   * Page title callback for a Customers revision.
   *
   * @param int $customers_revision
   *   The Customers revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($customers_revision) {
    $customers = $this->entityTypeManager()->getStorage('customers')
      ->loadRevision($customers_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $customers->label(),
      '%date' => $this->dateFormatter->format($customers->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Customers.
   *
   * @param \Drupal\decoupled_skeleton\Entity\CustomersInterface $customers
   *   A Customers object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(CustomersInterface $customers) {
    $account = $this->currentUser();
    $customers_storage = $this->entityTypeManager()->getStorage('customers');

    $langcode = $customers->language()->getId();
    $langname = $customers->language()->getName();
    $languages = $customers->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $customers->label()]) : $this->t('Revisions for %title', ['%title' => $customers->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all customers revisions") || $account->hasPermission('administer customers entities')));
    $delete_permission = (($account->hasPermission("delete all customers revisions") || $account->hasPermission('administer customers entities')));

    $rows = [];

    $vids = $customers_storage->revisionIds($customers);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\decoupled_skeleton\CustomersInterface $revision */
      $revision = $customers_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $customers->getRevisionId()) {
          $link = $this->l($date, new Url('entity.customers.revision', [
            'customers' => $customers->id(),
            'customers_revision' => $vid,
          ]));
        }
        else {
          $link = $customers->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.customers.translation_revert', [
                'customers' => $customers->id(),
                'customers_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.customers.revision_revert', [
                'customers' => $customers->id(),
                'customers_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.customers.revision_delete', [
                'customers' => $customers->id(),
                'customers_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['customers_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
