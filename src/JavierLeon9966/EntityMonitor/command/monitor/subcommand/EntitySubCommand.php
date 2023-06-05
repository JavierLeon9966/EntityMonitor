<?php

declare(strict_types = 1);

namespace JavierLeon9966\EntityMonitor\command\monitor\subcommand;

use pocketmine\command\CommandSender;

class EntitySubCommand extends BaseMonitorSubCommand{

	/** @param array<array-key, mixed> $args */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
		/** @var bool $ticking */
		$ticking = $args['ticking'] ?? false;
		$this->sendChunksInfo($sender, true, $args['ticking'] ?? false);
	}

	public function prepare(): void{
		$this->setPermission('entitymonitor.command.monitor.entity');
		parent::prepare();
	}
}