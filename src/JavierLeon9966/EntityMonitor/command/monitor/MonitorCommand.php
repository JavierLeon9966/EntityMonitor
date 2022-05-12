<?php

declare(strict_types = 1);

namespace JavierLeon9966\EntityMonitor\command\monitor;

use CortexPE\Commando\BaseCommand;

use JavierLeon9966\EntityMonitor\command\monitor\subcommand\{EntitySubCommand, TileSubCommand};

use pocketmine\command\CommandSender;

class MonitorCommand extends BaseCommand{

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
		$this->sendUsage();
	}

	protected function prepare(): void{
		$this->setPermission('entitymonitor.command.monitor.entity;entitymonitor.command.monitor.tile');
		$this->registerSubCommand(new EntitySubCommand($this->plugin, 'entity'));
		$this->registerSubCommand(new TileSubCommand($this->plugin, 'tile'));
	}
}
