<?php

namespace Drupal\az_blob_fs;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Database\Connection;

class AzBlobService {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The config factory object.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructs an S3fsService object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The new database connection object.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory object.
   */
  public function __construct(Connection $connection, ConfigFactory $config_factory) {
    $this->connection = $connection;
    $this->configFactory = $config_factory;
  }

  public function getAzBlobProxyClient(array $config) {
    $connectionString = "DefaultEndpointsProtocol=https;AccountName={$config['AccountName']};AccountKey={$config['AccountKey']}";
    return BlobRestProxyAlter::createBlobService($connectionString);
  }
}