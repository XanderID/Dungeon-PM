<?php

namespace Sub2GamingAqua\DungeonX;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\scheduler\Task;

class DelayTask extends Task {

    public $block, $plugin;

    public function __construct(Main $plugin, Block $block) {
        $this->plugin = $plugin;
        $this->block = $block;
    }

    public function onRun() : void{
        $this->block->getPosition()->getWorld()->setBlock($this->block->getPosition()->asVector3(), BlockFactory::getInstance()->get($this->block->getId()));
    }
}
