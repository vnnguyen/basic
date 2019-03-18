<?php

$vendorDir = dirname(__DIR__);

return array (
  'yiisoft/yii2-bootstrap4' => 
  array (
    'name' => 'yiisoft/yii2-bootstrap4',
    'version' => '2.0.0.0',
    'alias' => 
    array (
      '@yii/bootstrap4' => $vendorDir . '/yiisoft/yii2-bootstrap4/src',
    ),
  ),
  'creocoder/yii2-nested-sets' => 
  array (
    'name' => 'creocoder/yii2-nested-sets',
    'version' => '0.9.0.0',
    'alias' => 
    array (
      '@creocoder/nestedsets' => $vendorDir . '/creocoder/yii2-nested-sets/src',
    ),
  ),
  'creocoder/yii2-taggable' => 
  array (
    'name' => 'creocoder/yii2-taggable',
    'version' => '2.0.0.0',
    'alias' => 
    array (
      '@creocoder/taggable' => $vendorDir . '/creocoder/yii2-taggable/src',
    ),
  ),
  'bubasuma/yii2-simplechat' => 
  array (
    'name' => 'bubasuma/yii2-simplechat',
    'version' => '2.0.0.0',
    'alias' => 
    array (
      '@bubasuma/simplechat' => $vendorDir . '/bubasuma/yii2-simplechat',
    ),
  ),
  'yiisoft/yii2-queue' => 
  array (
    'name' => 'yiisoft/yii2-queue',
    'version' => '2.1.0.0',
    'alias' => 
    array (
      '@yii/queue' => $vendorDir . '/yiisoft/yii2-queue/src',
      '@yii/queue/amqp' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/amqp',
      '@yii/queue/amqp_interop' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/amqp_interop',
      '@yii/queue/beanstalk' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/beanstalk',
      '@yii/queue/db' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/db',
      '@yii/queue/file' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/file',
      '@yii/queue/gearman' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/gearman',
      '@yii/queue/redis' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/redis',
      '@yii/queue/sync' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/sync',
      '@yii/queue/sqs' => $vendorDir . '/yiisoft/yii2-queue/src/drivers/sqs',
    ),
  ),
  'yiisoft/yii2-authclient' => 
  array (
    'name' => 'yiisoft/yii2-authclient',
    'version' => '2.0.6.0',
    'alias' => 
    array (
      '@yii/authclient' => $vendorDir . '/yiisoft/yii2-authclient',
    ),
  ),
  'yiisoft/yii2-bootstrap' => 
  array (
    'name' => 'yiisoft/yii2-bootstrap',
    'version' => '2.0.9.0',
    'alias' => 
    array (
      '@yii/bootstrap' => $vendorDir . '/yiisoft/yii2-bootstrap/src',
    ),
  ),
  'yiisoft/yii2-debug' => 
  array (
    'name' => 'yiisoft/yii2-debug',
    'version' => '2.0.14.0',
    'alias' => 
    array (
      '@yii/debug' => $vendorDir . '/yiisoft/yii2-debug/src',
    ),
  ),
  'yiisoft/yii2-imagine' => 
  array (
    'name' => 'yiisoft/yii2-imagine',
    'version' => '2.0.4.0',
    'alias' => 
    array (
      '@yii/imagine' => $vendorDir . '/yiisoft/yii2-imagine',
    ),
  ),
  'yiisoft/yii2-swiftmailer' => 
  array (
    'name' => 'yiisoft/yii2-swiftmailer',
    'version' => '2.1.2.0',
    'alias' => 
    array (
      '@yii/swiftmailer' => $vendorDir . '/yiisoft/yii2-swiftmailer/src',
    ),
  ),
  'yiisoft/yii2-gii' => 
  array (
    'name' => 'yiisoft/yii2-gii',
    'version' => '2.0.8.0',
    'alias' => 
    array (
      '@yii/gii' => $vendorDir . '/yiisoft/yii2-gii/src',
    ),
  ),
  'kartik-v/yii2-mpdf' => 
  array (
    'name' => 'kartik-v/yii2-mpdf',
    'version' => '1.0.5.0',
    'alias' => 
    array (
      '@kartik/mpdf' => $vendorDir . '/kartik-v/yii2-mpdf/src',
    ),
  ),
  'kartik-v/yii2-widget-fileinput' => 
  array (
    'name' => 'kartik-v/yii2-widget-fileinput',
    'version' => '9999999-dev',
    'alias' => 
    array (
      '@kartik/file' => $vendorDir . '/kartik-v/yii2-widget-fileinput/src',
    ),
  ),
  'yiicod/yii2-base' => 
  array (
    'name' => 'yiicod/yii2-base',
    'version' => '1.0.7.0',
    'alias' => 
    array (
      '@yiicod/base' => $vendorDir . '/yiicod/yii2-base',
    ),
  ),
  'yiicod/yii2-cron' => 
  array (
    'name' => 'yiicod/yii2-cron',
    'version' => '1.1.2.1',
    'alias' => 
    array (
      '@yiicod/cron' => '/',
    ),
  ),
  'yiicod/yii2-mailqueue' => 
  array (
    'name' => 'yiicod/yii2-mailqueue',
    'version' => '1.1.1.0',
    'alias' => 
    array (
      '@yiicod/mailqueue' => '/',
    ),
  ),
  'webzop/yii2-notifications' => 
  array (
    'name' => 'webzop/yii2-notifications',
    'version' => '0.2.0.0',
    'alias' => 
    array (
      '@webzop/notifications' => $vendorDir . '/webzop/yii2-notifications',
    ),
    'bootstrap' => 'webzop\\notifications\\Bootstrap',
  ),
  'kartik-v/yii2-krajee-base' => 
  array (
    'name' => 'kartik-v/yii2-krajee-base',
    'version' => '2.0.5.0',
    'alias' => 
    array (
      '@kartik/base' => $vendorDir . '/kartik-v/yii2-krajee-base/src',
    ),
  ),
);
