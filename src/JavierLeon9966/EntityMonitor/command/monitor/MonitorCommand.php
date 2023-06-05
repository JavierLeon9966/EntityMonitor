<?php

declare(strict_types = 1);

namespace JavierLeon9966\EntityMonitor\command\monitor;

use CortexPE\Commando\BaseCommand;

use JavierLeon9966\EntityMonitor\command\monitor\subcommand\{EntitySubCommand, TileSubCommand};

use JavierLeon9966\EntityMonitor\EntityMonitor;
use pocketmine\command\CommandSender;

class MonitorCommand extends BaseCommand{

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
		$this->sendUsage();
	}

	protected function prepare(): void{
		$this->setPermissions(['entitymonitor.command.monitor.entity', 'entitymonitor.command.monitor.tile']);
		$plugin = $this->getOwningPlugin();
		assert($plugin instanceof EntityMonitor);
		$this->registerSubCommand(new EntitySubCommand($plugin, 'entity'));
		$this->registerSubCommand(new TileSubCommand($plugin, 'tile'));
	}
}
