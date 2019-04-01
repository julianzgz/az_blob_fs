<?php

namespace Drupal\az_blob_fs\Controller;

use Drupal\az_blob_fs\AzBlobService;
use Drupal\az_blob_fs\BlobRestProxyAlter;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Driver\mysql\Connection;
use Exception;
use MicrosoftAzure\Storage\Blob\Models\Block;
use MicrosoftAzure\Storage\Blob\Models\BlockList;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SandboxController.
 */
class SandboxController extends ControllerBase {

  /**
   * Drupal\az_blob_fs\AzBlobService definition.
   *
   * @var \Drupal\az_blob_fs\AzBlobService
   */
  protected $azBlob;

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new SandboxController object.
   */
  public function __construct(AzBlobService $az_blob, Connection $database, ConfigFactoryInterface $config_factory) {
    $this->azBlob = $az_blob;
    $this->database = $database;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('az_blob'),
      $container->get('database'),
      $container->get('config.factory')
    );
  }

  /**
   * Sandbox.
   *
   * @return array|string|object|null
   *   Return Hello string.
   */
  public function sandbox() {
    $connectionString = "";
    $blobClient = BlobRestProxyAlter::createBlobService($connectionString);

    $block_id = base64_encode('az_blob_fssde');
    $blocks = [new Block($block_id, 'Uncommitted')];
    $blocksList = BlockList::create($blocks);

    $file = fopen('/Users/mihai.iorga/Sites/az_blob_fs/web/modules/custom/az_blob_fs/az_blob_fs.info.yml', 'r');

    try {
      $blobClient->createBlobBlock('az-drupal', 'gigi.module', $block_id, $file);
    } catch (Exception $exception) {
      kint($exception);
      die();
    }

    $blobClient->commitBlobBlocks('az-drupal', 'gigi.module', $blocksList);


    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: sandbox'),
    ];
  }

}
