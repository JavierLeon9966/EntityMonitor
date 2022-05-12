<?php

namespace JavierLeon9966\EntityMonitor\command\monitor\subcommand;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\BaseSubCommand;

use JavierLeon9966\EntityMonitor\EntityMonitor;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;

abstract class BaseMonitorSubCommand extends BaseSubCommand{

	protected function sendChunksInfo(CommandSender $sender, bool $searchForEntities, bool $ticking): void{
		$getChunk =  \Closure::fromCallable([EntityMonitor::class, $searchForEntities ?
			'getChunkWithMostEntities' :
			'getChunkWithMostTiles'
		]);

		$extraMessage = ' ' . ($searchForEntities ? 'entities' : 'tiles') . ($ticking ? ' ticking' : '');

		foreach($sender->getServer()->getWorldManager()->getWorlds() as $world){
			$folderName = $world->getFolderName();
			$displayName = $world->getDisplayName();
			$levelName = $folderName !== $displayName ? " ($displayName)" : '';
			[$chunkHash, $count] = $getChunk($world, $ticking);
			if($chunkHash === null){
				$sender->sendMessage(TextFormat::RED."No chunks were found in world \"$folderName\"$levelName");
				continue;
			}
			World::getXZ($chunkHash, $chunkX, $chunkZ);

			$sender->sendMessage(TextFormat::GOLD."World \"$folderName\"$levelName: ".
				"Chunk \"$chunkX, $chunkZ\": ".TextFormat::RED.number_format($count).
				TextFormat::GREEN.$extraMessage
			);
		}
	}

	public function prepare(): void{
		$this->registerArgument(0, new BooleanArgument('ticking', true));
	}
}