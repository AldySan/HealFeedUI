<?php

namespace AldySan\HealFeedUI;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\command\ConsoleCommandSender;

use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\AnvilBreakSound;
use pocketmine\level\sound\BlazeShootSound;

use AldySan\HealFeedUI\libs\jojoe77777\FormAPI\SimpleForm;
use onebone\economyapi\EconomyAPI;
use AldySan\HealFeedUI\libs\jojoe77777\FormAPI\CustomForm;

class Main extends PluginBase {
	public function onEnable(){
	@mkdir($this->getDataFolder());
	$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
	$this->saveResource('config.yml');
	if($config->exists("version") && $config->get("version") === "1.0"){
	$this->getLogger()->info("Your Config Up To Date!!");
	} else {
	$this->getLogger()->error("Your config is out of date!!, update config!!");
	$config->set("version", "1.0");
	$this->getServer()->getLogger()->info("Version Update 0%...");
	$config->set("nopermission-message", "§cUpgrade Your Rank To Unlock Feature Here!!");
	$this->getServer()->getLogger()->info("Update 10%...");
	$config->set("failedheal-message", "§l§cGAGAL!!§r§b Kamu Tidak Terluka!!");
	$this->getServer()->getLogger()->info("Update 20%...");
	$config->set("succesheal-message", "§l§aBERHASIL!!§r§b Kamu Telah Di Pulihkan!!");
	$this->getServer()->getLogger()->info("Update 25%...");
	$config->set("failedfeed-message", "§l§cGAGAL!!§r§b Kamu Tidak Lapar!!");
	$this->getServer()->getLogger()->info("Update 35%...");
	$config->set("succesfeed-message", "§l§aBERHASIL!!§r§b Kamu Sekarang Tidak Kelaparan Lagi!!");
	$this->getServer()->getLogger()->info("Update 40%...");
	$config->set("title", "§aHeal Feed UI");
	$this->getServer()->getLogger()->info("Update 45%...");
	$config->set("content", "§bSelect:");
	$this->getServer()->getLogger()->info("Update 50%...");
	$config->set("healbutton-unlocked", "§a-- USE --");
	$this->getServer()->getLogger()->info("Update 55%...");
	$config->set("feedbutton-unlocked", "§a-- USE --");
	$this->getServer()->getLogger()->info("Update 60%...");
	$config->set("healbutton-locked", "§cFor Ranks §a[VIP]§c Or Higher!");
	$this->getServer()->getLogger()->info("Update 65%...");
	$config->set("feedbutton-locked", "§cFor Ranks §6[MEMBER]§c Or Higher!");
	$this->getServer()->getLogger()->info("Update 70%...");
	$config->set("backbutton", "§6§lBack");
	$this->getServer()->getLogger()->info("Update 75%...");
	$config->set("exitbutton", "§c§lExit");
	$this->getServer()->getLogger()->info("Update 80%...");
	$config->set("healbutton-money", "§a-- Rp.50000 --");
	$this->getServer()->getLogger()->info("Update 85%...");
	$config->set("feedbutton-money", "§a-- Rp.50000 --");
	$this->getServer()->getLogger()->info("Update 90%...");
	$config->set("withpermission-button", "§a-- With Ranks --");
	$this->getServer()->getLogger()->info("Update 92%...");
	$config->set("withmoney-button", "§a-- With Money --");
	$this->getServer()->getLogger()->info("Update 94%...");
	$config->set("heal-money", 50000);
	$this->getServer()->getLogger()->info("Update 96%...");
	$config->set("feed-money", 50000);
	$this->getServer()->getLogger()->info("Update 98%...");
	$config->set("nomoney-message", "§cUang Mu Tidak Cukup!!");
	$this->getServer()->getLogger()->info("Update 100%...");
	$config->save();
	$this->getLogger()->info("Update Complete...");
	$this->getLogger()->info("Check Update Version in Poggit!...");
	}
	if((Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")) === null){
	$this->getLogger()->error("EconomyAPI Missing!! Required EconomyAPI!!");
	Server::getInstance()->getPluginManager()->disablePlugin($this);
	}
	if((Server::getInstance()->getPluginManager()->getPlugin("PurePerms")) === null){
	$this->getLogger()->error("PurePerms Missing!! Required PurePerms!!");
	Server::getInstance()->getPluginManager()->disablePlugin($this);
	}
	}
	public function onCompletion()
	{
	$this->getLogger()->info("Done!!, Check Update In Poggit!!");
	}
    public function onCommand(CommandSender $s, Command $command, String $label, Array $args) : bool
    {
        switch($command->getName()) {
            case "healfeed":
                    $this->healfeed($s);
                    return true;
                    break;
        }
        return false;
    }
    public function healfeed($player)
    {
        $form = new SimpleForm(function(Player $player, Int $data = null)
        {
	$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $this->withperm($player);
                    return true;
        break;
                case 1:
                    $this->withmoney($player);
                    return true;
        break;
		case 2:
	      return;
	break;
	}
        });
	$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $form->setTitle($config->get("title"));
        $form->setContent($config->get("content"));
	    $form->addButton($config->get("withpermission-button"));
	    $form->addButton($config->get("withmoney-button"));
	    $form->addButton($config->get("exitbutton"));
        $form->sendToPlayer($player);
    }
    public function withperm($player)
    {
        $form = new SimpleForm(function(Player $player, Int $data = null)
        {
	$fpls = $player->getFood();
	$hpls = $player->getHealth();
	$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
        if (!$player->hasPermission("healui.use")) {
            $player->sendMessage($config->get("nopermission-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}elseif ($hpls === 20 || $hpls > 18){
	        $player->sendMessage($config->get("failedheal-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}else{
            	$player->setHealth(20);
           	$player->getLevel()->addSound(new AnvilBreakSound($player));
                $player->sendMessage($config->get("succesheal-message"));
	}
        break;
                case 1:
        if (!$player->hasPermission("feedui.use")) {
            $player->sendMessage($config->get("nopermission-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}elseif ($fpls === 20 || $fpls > 18){
	        $player->sendMessage($config->get("failedfeed-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}else{
            	$player->setFood(20);
       		$player->setSaturation(20);
           	$player->getLevel()->addSound(new AnvilBreakSound($player));
                $player->sendMessage($config->get("succesfeed-message"));
	}
        break;
		case 2:
	      $this->healfeed($player);
	     return true;
	break;
	}
        });
	$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $form->setTitle($config->get("title"));
        $form->setContent($config->get("content"));
        if (!$player->hasPermission("healui.use")) {
            $form->addButton("§6--§a Heal§6 --\n{$config->get("healbutton-locked")}");
	}else{
            $form->addButton("§6--§a Heal§6 --\n§{$config->get("healbutton-unlocked")}");
	}
        if (!$player->hasPermission("feedui.use")) {
            $form->addButton("§6--§a Feed§6 --\n{$config->get("feedbutton-locked")}");
	}else{
            $form->addButton("§6--§a Feed§6 --\n{$config->get("feedbutton-unlocked")}");
	}
	    $form->addButton($config->get("backbutton"));
        $form->sendToPlayer($player);
    }
    public function withmoney($player)
    {
        $form = new SimpleForm(function(Player $player, Int $data = null)
        {
	$fpls = $player->getFood();
	$hpls = $player->getHealth();
	$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
	$economy = Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI");
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
        if ($economy->reduceMoney($player, $config->get("heal-money"))) {
            	$player->setHealth(20);
           	$player->getLevel()->addSound(new AnvilBreakSound($player));
                $player->sendMessage($config->get("succesheal-message"));
	}elseif ($hpls === 20 || $hpls > 18){
	        $player->sendMessage($config->get("failedheal-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}else{
	        $player->sendMessage($config->get("nomoney-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}
        break;
                case 1:
        if ($economy->reduceMoney($player, $config->get("feed-money"))) {
            	$player->setFood(20);
       		$player->setSaturation(20);
           	$player->getLevel()->addSound(new AnvilBreakSound($player));
                $player->sendMessage($config->get("succesfeed-message"));
	}elseif ($fpls === 20 || $fpls > 18){
	        $player->sendMessage($config->get("failedfeed-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}else{
	        $player->sendMessage($config->get("nomoney-message"));
           	$player->getLevel()->addSound(new AnvilFallSound($player));
	}
        break;
		case 2:
	      	      $this->healfeed($player);
	     return true;
	break;
	}
        });
	$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $form->setTitle($config->get("title"));
        $form->setContent($config->get("content"));
            $form->addButton("§6--§a Heal§6 --\n{$config->get("healbutton-money")}");
            $form->addButton("§6--§a Feed§6 --\n{$config->get("feedbutton-money")}");
	    $form->addButton($config->get("backbutton"));
        $form->sendToPlayer($player);
    }
}
