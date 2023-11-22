<?php

use Contributte\Nextras\Orm\Generator\Analyser\Database\DatabaseAnalyser;
use Contributte\Nextras\Orm\Generator\Config\Impl\SeparateConfig;
use Contributte\Nextras\Orm\Generator\Config\Impl\TogetherConfig;
use Contributte\Nextras\Orm\Generator\SimpleFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$factory = new SimpleFactory(
	new TogetherConfig([
		'output' => __DIR__ . '/../app/Model/Orm',
		'generator.generate.facades' => false,
		'generator.entity.exclude.primary' => false,
		'entity.namespace' => 'App\Model\Orm',
		'repository.namespace' => 'App\Model\Orm',
		'mapper.namespace' => 'App\Model\Orm',
		'model.namespace' => 'App\Model\Orm',
		'orm.namespace' => 'App\Model\Orm',
		'entity.extends' => 'App\Model\Orm\AbstractEntity',
		'repository.extends' => 'App\Model\Orm\AbstractRepository',
		'mapper.extends' => 'App\Model\Orm\AbstractMapper',
		'entity.name.singularize' => true,
		'generator.generate.model' => false,
		'entity.generate.relations' => false
	]),
	new DatabaseAnalyser('mysql:host=4iz571-eshop-db-1;dbname=eshop', 'user', 'user')
);

$factory->create()->generate();