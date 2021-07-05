<?php

declare(strict_types = 1);

namespace JavierLeon9966\EntityMonitor;

use pocketmine\command\{Command, CommandSender};
use pocketmine\entity\Entity;
use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;

final class EntityMonitor extends PluginBase{
	public static function getMonitoredEntities(Level $level, bool $searchEntities = true, bool $ticking = false, ?int &$count = null): ?Chunk{
		$count = 0;

		if($searchEntities){
			if($ticking){
				$chunks = array_map(function(Chunk $chunk) use($level): int{
					return count(array_filter($chunk->getEntities(), function(Entity $entity) use($level): bool{
						return isset($level->updateEntities[$entity->getId()]);
					}));
				}, $level->getChunks());
			}else{
				$chunks = array_map(function(Chunk $chunk): int{
					return count($chunk->getEntities());
				}, $level->getChunks());
			}
		}else{
			if($ticking){
				$chunks = array_map(function(Chunk $chunk) use($level): int{
					return count(array_filter($chunk->getTiles(), function(Tile $tile) use($level): bool{
						return isset($level->updateTiles[$tile->getId()]);
					}));
				}, $level->getChunks());
			}else{
				$chunks = array_map(function(Chunk $chunk): int{
					return count($chunk->getTiles());
				}, $level->getChunks());
			}
		}
		$index = count($chunks) > 0 ? array_search($count = max($chunks), $chunks, true) : false;
		if($index === false){
			return null;
		}
	
		Level::getXZ($index, $chunkX, $chunkZ);
		return $level->getChunk($chunkX, $chunkZ);
	}

	public function onCommand(CommandSender $sender, Command $command, string $commandLabel, array $args): bool{
		if(count($args) > 2){
			return false;
		}

		$search = strtolower($args[0] ?? 'entity');
		if(!in_array($search, ['entity', 'tile'])){
			return false;
		}

		$tickingParemeter = strtolower($args[1] ?? 'false');
		if(!in_array($tickingParemeter, ['false', 'true'])){
			return false;
		}
		$ticking = $tickingParemeter === 'true';

		foreach($this->getServer()->getLevels() as $level){
			$levelName = $level->getFolderName() !== $level->getName() ? " ({$level->getName()})" : '';
			$chunk = self::getMonitoredEntities($level, $search === 'entity', $ticking, $count);
			if($chunk === null){
				$sender->sendMessage(TextFormat::RED."No chunks were found in world \"{$level->getFolderName()}\"$levelName");
				continue;
			}

			$count = $search === 'entity' ?
				TextFormat::RED.number_format($count).TextFormat::GREEN.' entities' :
				TextFormat::RED.number_format($count).TextFormat::GREEN.' tiles';
			$sender->sendMessage(TextFormat::GOLD."World \"{$level->getFolderName()}\"$levelName: ".
				"Chunk \"{$chunk->getX()}, {$chunk->getZ()}\": $count".
				($ticking ? ' ticking' : '')
			);
		}

		return true;
	}
}
