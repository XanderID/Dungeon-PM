<?php

namespace XanderID\Dungeon;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\BlockFactory;

use pocketmine\scheduler\ClosureTask;

class Dungeon extends PluginBase implements Listener{

	/* @var Config $config */
    private $config;

    public function onEnable() : void
    {
        $this->saveResource("config.yml");
        
        $this->config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function checkBlock(Block $block): bool{
    	if($block->getId() === 16 && $this->config->get("coal_ore") === true){
            return true;
        } else if($block->getId() === 15 && $this->config->get("iron_ore") === true){
            return true;
        } else if($block->getId() === 14 && $this->config->get("gold_ore") === true){
            return true;
        } else if($block->getId() === 56 && $this->config->get("diamond_ore") === true){
            return true;
        } else if($block->getId() === 129 && $this->config->get("emerald_ore") === true){
            return true;
        } else if($block->getId() === 153 && $this->config->get("quartz_ore") === true){
            return true;
        } else if($block->getId() === 21 && $this->config->get("lapis_ore") == true){
            return true;
        } else if($block->getId() === 142 && $this->config->get("potato_block") === true){
            return true;
        } else if($block->getId() === 141 && $this->config->get("carrot_block") === true){
            return true;
        } else if($block->getId() === 103 && $this->config->get("melon_block") === true){
            return true;
        } else if(in_array($block->getId(), [83, 0]) && $this->config->get("sugarcane") === true){
            return true;
        } else if($block->getId() === 81 && $this->config->get("cactus") === true){
            return true;
        }
        
        return false;
    }

	/** @priority HIGHEST */
    public function onBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $pos = $event->getBlock()->getPosition();
        if($pos->getWorld()->getFolderName() !== $this->config->get("levelname")) return false;
        if(!$this->checkBlock($block)) return false;
        
        foreach ($event->getDrops() as $drop) {
        	$player->getInventory()->addItem($drop);
        }
        
        if($block->getId() === 81){
        	$block->getWorld()->setBlock($block->asVector3(), VanillaBlocks::AIR());
        } else {
        	$block->getWorld()->setBlock($block->asVector3(), VanillaBlocks::STONE());
        }
        
        $event->setDrops([]);
        $event->cancel();
        $event->setXpDropAmount(0);
        $player->getXpManager()->addXp($event->getXpDropAmount());
        
        $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
        	function () use($pos, $block){
            	$pos->getWorld()->setBlock($pos->asVector3(), BlockFactory::getInstance()->get($block->getId()));
       	 }
        ), 20 * $this->config->get("delay"));
    }
}
