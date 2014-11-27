<?php

namespace DotBlue\Mpdf\DI;

use Nette\DI;


class Extension extends DI\CompilerExtension
{

	/** @var array */
	private $defaults = [];



	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$templatesDir = $config['templatesDir'];
		unset($config['templatesDir']);

		$themes = $config['themes'];
		unset($config['themes']);

		if (!$container->getByType('DotBlue\Mpdf\ITemplateFactory')) {
			$container->addDefinition($this->prefix('templateFactory'))
				->setClass('DotBlue\Mpdf\TemplateFactories\DefaultLatteTemplateFactory');
		}

		$factory = $container->addDefinition($this->prefix('factory'))
			->setClass('DotBlue\Mpdf\Factory', [
				$templatesDir,
				$config,
			]);

		foreach ($themes as $name => $setup) {
			$factory->addSetup('addTheme', [
				$name,
				$setup,
			]);
		}
	}

}