<?php

namespace Core\util;

use Core\Core;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class InfoTask extends Task {

    public function __construct(Core $plugin) {
        $this->plugin = $plugin;
        $this->util = new Util($this->plugin);
    }

    public function onRun($currentTick) {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $name = $player->getName();
            if (!isset($this->plugin->data[$name])) {
                $player->sendTip("로딩중...");
            }
            if (isset($this->plugin->data[$name])) {
                if (!$this->util->iswar($player->getName())) {
                    $this->util->autoHeal($player);
                }
                $this->plugin->util->setInfo($player->getName());
                if ($player->getGamemode() == 1 || $player->getGamemode() == 3) {
                    $player->sendTip("\n\n\n\n\n\n\n\n" . $this->util->HealthBar($player) . "     " . $this->util->ManaBar($player));
                } else {
                    $player->sendTip("\n\n\n\n\n\n" . $this->util->HealthBar($player) . "     " . $this->util->ManaBar($player));
                }
            }
            if (!isset($this->plugin->time[$player->getName()]))
                $this->plugin->time[$player->getName()] = time();
            if (microtime(true) - $this->plugin->time[$player->getName()] > 1) {
                $this->plugin->sendBoard($player);
                $this->plugin->time[$player->getName()] = time();
            }
        }
    }

    public function ExpBar($player) {
        $name = $player->getName();
        $maxexp = $this->util->getMaxExp($name);
        $exp = $this->util->getExp($name);
        $o = $maxexp / 90;
        if ($o == 0) return "로딩중...";
        if ($maxexp == $exp) {
            $a = str_repeat("§a⎪§r", round($maxexp / $o));
            return $a;
        } elseif ($maxexp - $exp > 0) {
            $a = str_repeat("§a⎪§r", round($exp / $o)) . str_repeat("§0⎪§r", round($maxexp / $o - $exp / $o));
            return $a;
        }
    }
}
