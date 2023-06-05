# EntityMonitor
Plugin that monitors every chunk with potential amount of entities and tiles

This plugin is very simple that the only thing you need is one command:

/monitor [entity|tile] [ticking: bool]

## API
```php
use JavierLeon9966\EntityMonitor\EntityMonitor;
use pocketmine\world\World;

[$chunkHash, $entityCount] = EntityMonitor::getChunkWithMostEntities(World $world, bool $ticking): array{?int, int};

[$chunkHash, $entityCount] = EntityMonitor::getChunkWithMostTiles(World $world, bool $ticking): array{?int, int};
```