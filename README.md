# EntityMonitor
Plugin that monitors every chunk with potential amount of entities and tiles

This plugin is very simple that the only thing you need is one command:

/entity [entity|tile] [ticking: bool]

## API
```php
use JavierLeon9966\EntityMonitor\EntityMonitor;
use pocketmine\level\Level;

$chunk = EntityMonitor::getMonitoredEntities(Level $level, bool $entities = true, bool $ticking = false, ?int &$count = null): ?Chunk;
```