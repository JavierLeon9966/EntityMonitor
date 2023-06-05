<?php

declare(strict_types = 1);

namespace JavierLeon9966\EntityMonitor;

use CortexPE\Commando\PacketHooker;

use JavierLeon9966\EntityMonitor\command\monitor\MonitorCommand;

use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\block\tile\Tile;
use pocketmine\world\World;

final class EntityMonitor extends PluginBase{

	protected function onEnable(): void{
		if(!PacketHooker::isRegistered()) PacketHooker::register($this);
		$this->getServer()->getCommandMap()->register('EntityMonitor', new MonitorCommand($this, 'monitor', 'Monitor levels that has chunks with most entities and tiles'));
	}

	/**
	 * @return (int|null)[]
	 * @phpstan-return array{0: int|null, 1: int}
	 */
	public static function getChunkWithMostEntities(World $world, bool $ticking): array{
		$count = count(...);
		$callbackFilter = fn(Entity $entity) => isset($world->updateEntities[$entity->getId()]);

		$countEntities = $ticking ? $count : fn(array $entities) => $count(array_filter($entities, $callbackFilter));

		$currentChunkHash = null;
		$currentCount = -1;
		foreach($world->getLoadedChunks() as $chunkHash => $chunk){
			World::getXZ($chunkHash, $chunkX, $chunkZ);
			$count = $countEntities($world->getChunkEntities($chunkX, $chunkZ));
			if($count > $currentCount){
				$currentChunkHash = $chunkHash;
				$currentCount = $count;
			}
		}
		return [$currentChunkHash, $currentCount];
	}

	/**
	 * @return (int|null)[]
	 * @phpstan-return array{0: int|null, 1: int}
	 */
	public static function getChunkWithMostTiles(World $world, bool $ticking): array{
		$prop = new \ReflectionProperty(World::class, 'scheduledBlockUpdateQueueIndex');
		$scheduledBlockUpdateQueueIndex = $prop->getValue($world);
		$count = count(...);
		$callbackFilter = fn(Tile $tile) => isset($scheduledBlockUpdateQueueIndex[World::blockHash(($pos = $tile->getPosition())->x, $pos->y, $pos->z)]);

		$countTiles = $ticking ? $count : fn(array $tiles) => $count(array_filter($tiles, $callbackFilter));

		$currentChunkHash = null;
		$currentCount = -1;
		foreach($world->getLoadedChunks() as $chunkHash => $chunk){
			$count = $countTiles($chunk->getTiles());
			if($count > $currentCount){
				$currentChunkHash = $chunkHash;
				$currentCount = $count;
			}
		}
		return [$currentChunkHash, $currentCount];
	}
}
