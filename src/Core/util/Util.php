<?php

namespace Core\util;

use Core\Core;
use pocketmine\entity\Attribute;
use pocketmine\entity\AttributeMap;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\Server;

class Util {
    public $exp = [];

    public function __construct(Core $plugin) {
        $this->plugin = $plugin;
    }

    public function war(string $name) {
        $this->plugin->war[$name]["전투"] = time();
    }

    public function iswar(string $name) {
        if (isset($this->plugin->war[$name]) && time() - $this->plugin->war[$name]["전투"] < 60)
            return true;
        else
            return false;
    }

    public function isRegistered(string $name) {
        return isset($this->plugin->data[$name]);
    }

    public function getHp($name) {
        return $this->plugin->data [$name] ["Hp"];
    }

    public function getAllExp($name) {
        return $this->plugin->data [$name] ["AllExp"];
    }

    public function addHp($name, int $amount) {
        $this->plugin->data [$name] ["Hp"] += $amount;
    }

    public function getATK($name) {
        $value = $this->plugin->data [$name] ["ATK"] + $this->plugin->data [$name] ["Stat_ATK"] + $this->plugin->data [$name] ["Skill_ATK"] + $this->plugin->data [$name] ["EquipmentRing_ATK"] + $this->plugin->data [$name] ["EquipmentPendant_ATK"];
        if ($this->getATKPer($name) < 0)
            return $value - ($value * abs($this->getATKPer($name)) / 100);
        elseif ($this->getATKPer($name) > 0)
            return $value + ($value * abs($this->getATKPer($name)) / 100);
        return $value;
    }

    public function getATKPer($name) {
        return $this->plugin->data [$name] ["Stat_ATK_Per"] + $this->plugin->data [$name] ["Skill_ATK_Per"] + $this->plugin->data [$name] ["EquipmentRing_ATK_Per"] + $this->plugin->data [$name] ["EquipmentPendant_ATK_Per"];
    }

    public function getDEF($name) {
        $value = $this->plugin->data [$name] ["DEF"] + $this->plugin->data [$name] ["Stat_DEF"] + $this->plugin->data [$name] ["Skill_DEF"] + $this->plugin->data [$name] ["EquipmentRing_DEF"] + $this->plugin->data [$name] ["EquipmentPendant_DEF"];
        if ($this->getDEFPer($name) < 0)
            return $value - ($value * abs($this->getDEFPer($name)) / 100);
        elseif ($this->getDEFPer($name) > 0)
            return $value + ($value * abs($this->getDEFPer($name)) / 100);
        return $value;
    }

    public function getDEFPer($name) {
        return $this->plugin->data [$name] ["Stat_DEF_Per"] + $this->plugin->data [$name] ["Skill_DEF_Per"] + $this->plugin->data [$name] ["EquipmentRing_DEF_Per"] + $this->plugin->data [$name] ["EquipmentPendant_DEF_Per"];
    }

    public function getMATK($name) {
        $value = $this->plugin->data [$name] ["MATK"] + $this->plugin->data [$name] ["Stat_MATK"] + $this->plugin->data [$name] ["Skill_MATK"] + $this->plugin->data [$name] ["EquipmentRing_MATK"] + $this->plugin->data [$name] ["EquipmentPendant_MATK"];
        if ($this->getMATKPer($name) < 0)
            return $value - ($value * abs($this->getMATKPer($name)) / 100);
        elseif ($this->getMATKPer($name) > 0)
            return $value + ($value * abs($this->getMATKPer($name)) / 100);
        return $value;
    }

    public function getMATKPer($name) {
        return $this->plugin->data [$name] ["Stat_MATK_Per"] + $this->plugin->data [$name] ["Skill_MATK_Per"] + $this->plugin->data [$name] ["EquipmentRing_MATK_Per"] + $this->plugin->data [$name] ["EquipmentPendant_MATK_Per"];
    }

    public function getMDEF($name) {
        $value = $this->plugin->data [$name] ["MDEF"] + $this->plugin->data [$name] ["Stat_MDEF"] + $this->plugin->data [$name] ["Skill_MDEF"] + $this->plugin->data [$name] ["EquipmentRing_MDEF"] + $this->plugin->data [$name] ["EquipmentPendant_MDEF"];
        if ($this->getMDEFPer($name) < 0)
            return $value - ($value * abs($this->getMDEFPer($name)) / 100);
        elseif ($this->getMDEFPer($name) > 0)
            return $value + ($value * abs($this->getMDEFPer($name)) / 100);
        return $value;
    }

    public function getMDEFPer($name) {
        return $this->plugin->data [$name] ["Stat_MDEF_Per"] + $this->plugin->data [$name] ["Skill_MDEF_Per"] + $this->plugin->data [$name] ["EquipmentRing_MDEF_Per"] + $this->plugin->data [$name] ["EquipmentPendant_MDEF_Per"];
    }

    public function getCritical($name) {
        $value = $this->plugin->data [$name] ["Critical"] + $this->plugin->data [$name] ["Stat_Critical"] + $this->plugin->data [$name] ["Skill_Critical"] + $this->plugin->data [$name] ["EquipmentRing_Critical"] + $this->plugin->data [$name] ["EquipmentPendant_Critical"];
        if ($this->getCriticalPer($name) < 0)
            return $value - ($value * abs($this->getCriticalPer($name)) / 100);
        elseif ($this->getCriticalPer($name) > 0)
            return $value + ($value * abs($this->getCriticalPer($name)) / 100);
        return $value;
    }

    public function getCriticalPer($name) {
        return $this->plugin->data [$name] ["Stat_Critical_Per"] + $this->plugin->data [$name] ["Skill_Critical_Per"] + $this->plugin->data [$name] ["EquipmentRing_Critical_Per"] + $this->plugin->data [$name] ["EquipmentPendant_Critical_Per"];
    }

    public function getEvasion($name) {
        $value = $this->plugin->data [$name] ["Evasion"] + $this->plugin->data [$name] ["Stat_Evasion"] + $this->plugin->data [$name] ["Skill_Evasion"] + $this->plugin->data [$name] ["EquipmentRing_Evasion"] + $this->plugin->data [$name] ["EquipmentPendant_Evasion"];
        if ($this->getEvasionPer($name) < 0)
            return $value - ($value * abs($this->getEvasionPer($name)) / 100);
        elseif ($this->getEvasionPer($name) > 0)
            return $value + ($value * abs($this->getEvasionPer($name)) / 100);
        return $value;
    }

    public function getEvasionPer($name) {
        return $this->plugin->data [$name] ["Stat_Evasion_Per"] + $this->plugin->data [$name] ["Skill_Evasion_Per"] + $this->plugin->data [$name] ["EquipmentRing_Evasion_Per"] + $this->plugin->data [$name] ["EquipmentPendant_Evasion_Per"];
    }

    public function getAttack($name) {
        $value = $this->plugin->data [$name] ["Attack"] + $this->plugin->data [$name] ["Stat_Attack"] + $this->plugin->data [$name] ["Skill_Attack"] + $this->plugin->data [$name] ["EquipmentRing_Attack"] + $this->plugin->data [$name] ["EquipmentPendant_Attack"];
        if ($this->getAttackPer($name) < 0)
            return $value - ($value * abs($this->getAttackPer($name)) / 100);
        elseif ($this->getAttackPer($name) > 0)
            return $value + ($value * abs($this->getAttackPer($name)) / 100);
        return $value;
    }

    public function getAttackPer($name) {
        return $this->plugin->data [$name] ["Stat_Attack_Per"] + $this->plugin->data [$name] ["Skill_Attack_Per"] + $this->plugin->data [$name] ["EquipmentRing_Attack_Per"] + $this->plugin->data [$name] ["EquipmentPendant_Attack_Per"];
    }

    public function getCD($name) {
        $value = $this->plugin->data [$name] ["CD"] + $this->plugin->data [$name] ["Stat_CD"] + $this->plugin->data [$name] ["Skill_CD"] + $this->plugin->data [$name] ["EquipmentRing_CD"] + $this->plugin->data [$name] ["EquipmentPendant_CD"];
        if ($this->getCDPer($name) < 0)
            return $value - ($value * abs($this->getCDPer($name)) / 100);
        elseif ($this->getCDPer($name) > 0)
            return $value + ($value * abs($this->getCDPer($name)) / 100);
        return $value;
    }

    public function getCDPer($name) {
        return $this->plugin->data [$name] ["Stat_CD_Per"] + $this->plugin->data [$name] ["Skill_CD_Per"] + $this->plugin->data [$name] ["EquipmentRing_CD_Per"] + $this->plugin->data [$name] ["EquipmentPendant_CD_Per"];
    }

    public function addMaxHp(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxHp"] += $amount;
    }

    public function addMaxMp(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxMp"] += $amount;
    }

    public function addATK(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_ATK"] += $amount;
    }
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////

    public function addMATK(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MATK"] += $amount;
    }

    public function addDEF(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_DEF"] += $amount;
    }

    public function addMDEF(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MDEF"] += $amount;
    }

    public function addEvasion(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Evasion"] += $amount;
    }

    public function addCD(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_CD"] += $amount;
    }

    public function addAttack(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Attack"] += $amount;
    }

    public function addCritical(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Critical"] += $amount;
    }

    public function addAutoHealHp(string $name, int $amount, string $type) {
        $this->plugin->data[$name]["{$type}_AutoHealHp"] += $amount;
    }

    public function addAutoHealMp(string $name, int $amount, string $type) {
        $this->plugin->data[$name]["{$type}_AutoHealMp"] += $amount;
    }

    public function addHitHealHp(string $name, int $amount, string $type) {
        $this->plugin->data[$name]["{$type}_HitHealHp"] += $amount;
    }

    public function addHitHealMp(string $name, int $amount, string $type) {
        $this->plugin->data[$name]["{$type}_HitHealMp"] += $amount;
    }

    public function addMaxHpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxHp_Per"] += $amount;
    }
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////

    public function addMaxMpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxMp_Per"] += $amount;
    }

    public function addATKPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_ATK_Per"] += $amount;
    }

    public function addMATKPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MATK_Per"] += $amount;
    }

    public function addDEFPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_DEF_Per"] += $amount;
    }

    public function addMDEFPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MDEF_Per"] += $amount;
    }

    public function addEvasionPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Evasion_Per"] += $amount;
    }

    public function addCDPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_CD_Per"] += $amount;
    }

    public function addAttackPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Attack_Per"] += $amount;
    }

    public function addCriticalPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Critical_Per"] += $amount;
    }

    public function addHitHealHpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_HitHealHp_Per"] += $amount;
    }

    public function addHitHealMpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_HitHealMp_Per"] += $amount;
    }

    public function setMaxHp(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxHp"] = $amount;
    }

    public function setMaxMp(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxMp"] = $amount;
    }

    public function setATK(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_ATK"] = $amount;
    }

    public function setMATK(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MATK"] = $amount;
    }

    public function setDEF(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_DEF"] = $amount;
    }

    public function setMDEF(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MDEF"] = $amount;
    }

    public function setEvasion(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Evasion"] = $amount;
    }

    public function setCD(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_CD"] = $amount;
    }

    public function setAttack(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Attack"] = $amount;
    }

    public function setCritical(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Critical"] = $amount;
    }

    public function setAutoHealHp(string $name, int $amount, string $type) {
        $this->plugin->data[$name]["{$type}_AutoHealHp"] = $amount;
    }

    public function setAutoHealMp(string $name, int $amount, string $type) {
        $this->plugin->data[$name]["{$type}_AutoHealMp"] = $amount;
    }

    public function setHitHealHp(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_HitHealHp"] = $amount;
    }

    public function setHitHealMp(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_HitHealMp"] = $amount;
    }

    public function setMaxHpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxHp_Per"] = $amount;
    }
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////

    public function setMaxMpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MaxMp_Per"] = $amount;
    }

    public function setATKPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_ATK_Per"] = $amount;
    }

    public function setMATKPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MATK_Per"] = $amount;
    }

    public function setDEFPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_DEF_Per"] = $amount;
    }

    public function setMDEFPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_MDEF_Per"] = $amount;
    }

    public function setEvasionPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Evasion_Per"] = $amount;
    }

    public function setCDPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_CD_Per"] = $amount;
    }

    public function setAttackPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Attack_Per"] = $amount;
    }

    public function setCriticalPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_Critical_Per"] = $amount;
    }

    public function setHitHealHpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_HitHealHp_Per"] = $amount;
    }

    public function setHitHealMpPer(string $name, int $amount, string $type) {
        $this->plugin->data [$name] ["{$type}_HitHealMp_Per"] = $amount;
    }

    public function addExp($name, int $amount, int $code = 0, $level = 0) {
        if (!isset($this->plugin->data [$name] ["Exp"])) return;
        if ($amount < 0) return;
        if (($player = Server::getInstance()->getPlayer($name)) instanceof Player)
            $this->ExpSound($player);
        //$player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP);
        if ($code == 1) {
            if ($level == 0) $amount *= 1;
            elseif ($level == 1) $amount *= 95 / 100;
            elseif ($level == 2) $amount *= 90 / 100;
            elseif ($level == 3) $amount *= 80 / 100;
            elseif ($level == 4) $amount *= 50 / 100;
            elseif ($level == 5) $amount *= 45 / 100;
            elseif ($level < 0) $amount *= 1;
            else $amount *= 30 / 100;
            if (isset($this->plugin->Etcitem->d[$name]["경험치포션"]))
                $amount *= 2;
            ceil($amount);
            if ($amount < 1)
                $amount = 1;
        }
        $amount = (int) $amount;
        if (($this->getExp($name) + $amount) < $this->getMaxExp($name)) {
            $this->plugin->data [$name] ["Exp"] += $amount;
            $this->AllExpFix($name);
            unset($this->exp[$name]);
            $this->setInfo($name);
            return;
        }
        if (($this->getExp($name) + $amount) >= $this->getMaxExp($name)) {
            if ($this->getLevel($name) == 200) {
                $this->setExp($name, $this->getMaxExp($name));
                $this->AllExpFix($name);
                return;
            } elseif ($this->getLevel($name) == 10 and $this->getJob($name) == "모험가") {
                $this->setExp($name, $this->getMaxExp($name));
                $this->AllExpFix($name);
                if (Server::getInstance()->getPlayer($name) instanceof Player) {
                    Server::getInstance()->getPlayer($name)->sendMessage("§e§l[ §f시스템 §e]§r§e 전직을 하셔야 레벨업을 할 수 있습니다!");
                }
                return;
            }
            $this->exp[$name] = 0;
            $a = ($this->getExp($name) + $amount);
            $b = $a - $this->getMaxExp($name);
            $this->AllExpFix($name);
            $this->levelup($name);
            if ($b == 0) {
                unset($this->exp[$name]);
                return;
            }
            $this->addExp($name, $b);
        }
    }

    private function ExpSound(Player $player) {
        $pk = new LevelSoundEventPacket();
        $pk->sound = LevelSoundEventPacket::SOUND_LEVELUP;
        $pk->position = $player;
        $pk->extraData = -1;
        $pk->isBabyMob = false;
        $pk->disableRelativeVolume = true;
        $player->dataPacket($pk);
    }

    public function getExp($name) {
        return $this->plugin->data [$name] ["Exp"];
    }

    public function setExp($name, int $amount) {
        if (!isset($this->plugin->data [$name] ["Exp"])) return;
        if ($amount < 0) return;
        if ($amount > $this->plugin->data [$name] ["MaxExp"]) {
            $this->plugin->data [$name] ["Exp"] = $this->getMaxExp($name);
            $this->AllExpFix($name);
            return;
        }
        $this->plugin->data [$name] ["Exp"] = $amount;
        $this->AllExpFix($name);
        $this->setInfo($name);
    }

    public function getMaxExp($name) {
        return $this->plugin->data [$name] ["MaxExp"];
    }

    public function AllExpFix($name) {
        $level = $this->getLevel($name);
        $a = 0;
        for ($i = 1; $i <= $level - 1; $i++) {
            $a += $this->plugin->maxexp [$i];
        }
        $this->plugin->data [$name] ["AllExp"] = ($a) + ($this->getExp($name));
    }

    public function getLevel($name) {
        return $this->plugin->data [$name] ["Level"];
    }

    public function setInfo($name) {
        if (!($player = Server::getInstance()->getPlayer($name)) instanceof Player) return;
        if (!$player->isAlive()) return;
        $name = $player->getName();
        if (!isset($this->plugin->data[$name])) return;
        if ($this->getMaxHp($name) == 0) return;
        if ($this->getLevel($name) == 0) return;
        if ($this->getMaxExp($name) == 0) return;
        if ($player->getHealth() > $player->getMaxHealth())
            $player->setHealth($player->getMaxHealth());
        if ($this->getMp($name) > $this->getMaxMp($name))
            $this->setMp($name, $this->getMaxMp($name));
        $player->setMaxHealth($this->getMaxHp($name));
        $player->setXpLevel($this->getLevel($name));
        $player->setXpProgress($this->getExp($name) / $this->getMaxExp($name));
    }

    public function getMaxHp($name) {
        $value = $this->plugin->data [$name] ["MaxHp"] + $this->plugin->data [$name] ["Stat_MaxHp"] + $this->plugin->data [$name] ["Skill_MaxHp"] + $this->plugin->data [$name] ["EquipmentRing_MaxHp"] + $this->plugin->data [$name] ["EquipmentPendant_MaxHp"];
        if ($this->getMaxHpPer($name) < 0)
            return $value - ($value * abs($this->getMaxHpPer($name)) / 100);
        elseif ($this->getMaxHpPer($name) > 0)
            return $value + ($value * abs($this->getMaxHpPer($name)) / 100);
        return $value;
    }

    public function getMaxHpPer($name) {
        return $this->plugin->data [$name] ["Stat_MaxHp_Per"] + $this->plugin->data [$name] ["Skill_MaxHp_Per"] + $this->plugin->data [$name] ["EquipmentRing_MaxHp_Per"] + $this->plugin->data [$name] ["EquipmentPendant_MaxHp_Per"];
    }

    public function getMp($name) {
        return $this->plugin->data [$name] ["Mp"];
    }

    public function getMaxMp($name) {
        $value = $this->plugin->data [$name] ["MaxMp"] + $this->plugin->data [$name] ["Stat_MaxMp"] + $this->plugin->data [$name] ["Skill_MaxMp"] + $this->plugin->data [$name] ["EquipmentRing_MaxMp"] + $this->plugin->data [$name] ["EquipmentPendant_MaxMp"];
        if ($this->getMaxMpPer($name) < 0)
            return $value - ($value * abs($this->getMaxMpPer($name)) / 100);
        elseif ($this->getMaxMpPer($name) > 0)
            return $value + ($value * abs($this->getMaxMpPer($name)) / 100);
        return $value;
    }

    public function getMaxMpPer($name) {
        return $this->plugin->data [$name] ["Stat_MaxMp_Per"] + $this->plugin->data [$name] ["Skill_MaxMp_Per"] + $this->plugin->data [$name] ["EquipmentRing_MaxMp_Per"] + $this->plugin->data [$name] ["EquipmentPendant_MaxMp_Per"];
    }

    public function setMp($name, int $amount) {
        if (!isset($this->plugin->data [$name] ["Mp"])) return;
        if ($amount < 0) return;
        if ($amount > $this->getMaxMp($name)) {
            $this->plugin->data [$name] ["Mp"] = $this->getMaxMp($name);
            return;
        }
        $this->plugin->data [$name] ["Mp"] = $amount;
        $this->setInfo($name);
    }

    public function getJob($name) {
        return $this->plugin->data [$name] ["Job"];
    }
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////

    public function levelup($name) {
        if ($this->getLevel($name) == 200) return;
        $this->plugin->data [$name] ["Level"] += 1;
        $this->setMaxMp_($name);
        $this->setMp($name, $this->getMaxMp($name));
        $this->setMaxExp($name);
        $this->setExp($name, 0);
        $this->AllExpFix($name);
        $this->setStat($name);
        $this->plugin->stat->addPoint($name, 5);
        $this->plugin->skill->addSkillPoint($name, 2);
        $this->setInfo($name);
        $this->plugin->skill->adjust_skill($name);
        $this->plugin->skill->check_skill($name);
        if (($player = Server::getInstance()->getPlayer($name)) instanceof Player)
            $this->ExpSound($player);
        //$player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP);
    }

    public function setMaxMp_($name) {
        if (!isset($this->plugin->data [$name] ["MaxMp"])) return;
        if ($this->getJob($name) == "모험가") $this->plugin->data [$name] ["MaxMp"] == 100;
        if ($this->getJob($name) == "나이트") $this->plugin->data [$name] ["MaxMp"] = explode(":", $this->plugin->knight [$this->getLevel($name)])[1];
        if ($this->getJob($name) == "아처") $this->plugin->data [$name] ["MaxMp"] = explode(":", $this->plugin->archer [$this->getLevel($name)])[1];
        if ($this->getJob($name) == "위자드") $this->plugin->data [$name] ["MaxMp"] = explode(":", $this->plugin->wizard [$this->getLevel($name)])[1];
        if ($this->getJob($name) == "프리스트") $this->plugin->data [$name] ["MaxMp"] = explode(":", $this->plugin->priest [$this->getLevel($name)])[1];
    }

    //$code -> 0: 퀘스트등의 일반 1: 몬스터사냥
    //$level -> $code가 1일경우에만 대입, 플레이어 레벨 - 몬스터 레벨

    public function setMaxExp($name) {
        if (!isset($this->plugin->data [$name] ["MaxExp"])) return;
        $this->plugin->data [$name] ["MaxExp"] = $this->plugin->maxexp [$this->getLevel($name)];
        $this->setInfo($name);
    }

    public function setStat($name) {
        if ($this->getJob($name) == "모험가") {
            $maxhp = explode(":", $this->plugin->user[$this->getLevel($name)])[0];
            $atk = explode(":", $this->plugin->user[$this->getLevel($name)])[2];
            $def = explode(":", $this->plugin->user[$this->getLevel($name)])[3];
            $matk = explode(":", $this->plugin->user[$this->getLevel($name)])[4];
            $mdef = explode(":", $this->plugin->user[$this->getLevel($name)])[5];
        }
        if ($this->getJob($name) == "나이트") {
            $maxhp = explode(":", $this->plugin->knight[$this->getLevel($name)])[0];
            $atk = explode(":", $this->plugin->knight[$this->getLevel($name)])[2];
            $def = explode(":", $this->plugin->knight[$this->getLevel($name)])[3];
            $matk = explode(":", $this->plugin->knight[$this->getLevel($name)])[4];
            $mdef = explode(":", $this->plugin->knight[$this->getLevel($name)])[5];
        }
        if ($this->getJob($name) == "아처") {
            $maxhp = explode(":", $this->plugin->archer[$this->getLevel($name)])[0];
            $atk = explode(":", $this->plugin->archer[$this->getLevel($name)])[2];
            $def = explode(":", $this->plugin->archer[$this->getLevel($name)])[3];
            $matk = explode(":", $this->plugin->archer[$this->getLevel($name)])[4];
            $mdef = explode(":", $this->plugin->archer[$this->getLevel($name)])[5];
        }
        if ($this->getJob($name) == "위자드") {
            $maxhp = explode(":", $this->plugin->wizard[$this->getLevel($name)])[0];
            $atk = explode(":", $this->plugin->wizard[$this->getLevel($name)])[2];
            $def = explode(":", $this->plugin->wizard[$this->getLevel($name)])[3];
            $matk = explode(":", $this->plugin->wizard[$this->getLevel($name)])[4];
            $mdef = explode(":", $this->plugin->wizard[$this->getLevel($name)])[5];
        }
        if ($this->getJob($name) == "프리스트") {
            $maxhp = explode(":", $this->plugin->priest[$this->getLevel($name)])[0];
            $atk = explode(":", $this->plugin->priest[$this->getLevel($name)])[2];
            $def = explode(":", $this->plugin->priest[$this->getLevel($name)])[3];
            $matk = explode(":", $this->plugin->priest[$this->getLevel($name)])[4];
            $mdef = explode(":", $this->plugin->priest[$this->getLevel($name)])[5];
        }
        $this->plugin->data[$name]["MaxHp"] = $maxhp;
        $this->plugin->data[$name]["ATK"] = $atk;
        $this->plugin->data[$name]["DEF"] = $def;
        $this->plugin->data[$name]["MATK"] = $matk;
        $this->plugin->data[$name]["MDEF"] = $mdef;
    }

    public function reduceMp($name, $amount) {
        if (!isset($this->plugin->data[$name]["Mp"])) return false;
        if ($amount < 0) return false;
        if ($this->getMp($name) < $amount)
            $this->plugin->data[$name]["Mp"] = 0;
        else
            $this->plugin->data[$name]["Mp"] -= $amount;
        $this->setInfo($name);
        return true;
    }

    public function reduceExp($name, $amount) {
        if (!isset($this->plugin->data[$name]["Exp"])) return false;
        if ($amount < 0) return false;
        if ($this->getExp($name) < $amount) return false;
        $this->plugin->data[$name]["Exp"] -= $amount;
        $this->AllExpFix($name);
        $this->setInfo($name);
        return true;
    }

    public function leveldown($name) {
        if ($this->getLevel($name) == 200) return;
        $this->plugin->data [$name] ["Level"] -= 1;
        $this->setMaxMp_($name);
        $this->setMp($name, $this->getMaxMp($name));
        $this->setMaxExp($name);
        $this->setExp($name, 0);
        $this->AllExpFix($name);
        $this->setStat($name);
    }

    public function setLevel($name, int $amount) {
        if ($amount > 200) $amount = 200;
        if ($amount < 0) return;
        if (!isset($this->plugin->data [$name] ["Level"])) return;
        $this->plugin->data [$name] ["Level"] = $amount;
        $this->setMaxMp_($name);
        $this->setMp($name, $this->getMaxMp($name));
        $this->setExp($name, 0);
        $this->setMaxExp($name);
        $this->AllExpFix($name);
        $this->setStat($name);
        return;
    }

    public function setJob($name, $job) {
        if (!isset($this->plugin->data [$name] ["Job"])) return;
        $this->plugin->data [$name] ["Job"] = $job;
        $this->setMaxMp_($name);
        $this->setMp($name, $this->getMaxMp($name));
        $this->setMaxExp($name);
        $this->setExp($name, $this->getExp($name));
        $this->AllExpFix($name);
        $this->setStat($name);
        if (Server::getInstance()->getPlayer($name) instanceof Player)
            Server::getInstance()->getPlayer($name)->setHealth($this->getMaxHp($name));
        $this->setInfo($name);
    }

    public function setJob_1($name, $job) {
        if (!isset($this->plugin->data [$name] ["Job"])) return;
        $this->plugin->data [$name] ["Job"] = $job;
    }

    public function setAllExp($name, int $amount) {
        if (!isset($this->plugin->data [$name] ["AllExp"])) return;
        if ($this->getLevel($name) < 10) return;
        if ($amount < 7300) $amount = 7300;
        if ($amount >= 788079706050) $amount = 788079706050;
        $s = 0;
        $s_1 = 0;
        for ($i = 1; $i <= 200; $i++) {
            $s += $this->plugin->maxexp[$i];
            if ($amount <= $s) {
                $this->plugin->data [$name] ["AllExp"] = $amount;
                $this->plugin->data [$name] ["Level"] = $i;
                $this->setMaxMp_($name);
                $this->setMp($name, $this->getMaxMp($name));
                $this->setMaxExp($name);
                $this->plugin->data [$name] ["Exp"] = $amount - ($s - ($this->plugin->maxexp[$i]));
                $this->setStat($name);
                $this->plugin->getServer()->getPlayer($name)->sendMessage("{$s}.{$amount}.{$this->plugin->maxexp[$i]}.{$i}");
                return;
            }
        }
    }

    public function autoHeal(Player $player) {
        if (!isset($this->plugin->cool[$player->getName()]["자연회복"]))
            $this->plugin->cool[$player->getName()]["자연회복"] = time();
        if (time() - $this->plugin->cool[$player->getName()]["자연회복"] >= 5) {
            $hpper = $this->getAutoHealHp($player->getName());
            $mpper = $this->getAutoHealMp($player->getName());
            if ($hpper <= 0)
                $hpper = 1;
            if ($mpper <= 0)
                $mpper = 1;
            $player->heal(new EntityRegainHealthEvent($player, $player->getMaxHealth() * ($hpper / 100), 3));
            $this->addMp($player->getName(), $this->getMaxMp($player->getName()) * ($mpper / 100));
            $this->plugin->cool[$player->getName()]["자연회복"] = time();
        }
    }

    public function getAutoHealHp(string $name) {
        return 5 + $this->plugin->data [$name] ["Stat_AutoHealHp"] + $this->plugin->data [$name] ["Skill_AutoHealHp"] + $this->plugin->data [$name] ["EquipmentRing_AutoHealHp"] + $this->plugin->data [$name] ["EquipmentPendant_AutoHealHp"];
    }

    public function getAutoHealMp(string $name) {
        return 5 + $this->plugin->data [$name] ["Stat_AutoHealMp"] + $this->plugin->data [$name] ["Skill_AutoHealMp"] + $this->plugin->data [$name] ["EquipmentRing_AutoHealMp"] + $this->plugin->data [$name] ["EquipmentPendant_AutoHealMp"];
    }

    public function addMp($name, int $amount) {
        if (!isset($this->plugin->data [$name] ["Mp"])) return;
        if ($amount < 0) return;
        if (($this->getMp($name) + $amount) >= $this->getMaxMp($name)) {
            $a = $this->getMaxMp($name) - $this->getMp($name);
            $this->plugin->data [$name] ["Mp"] += $a;
            return;
        }
        if (($this->getMp($name) + $amount) < $this->getMaxMp($name)) {
            $this->plugin->data [$name] ["Mp"] += $amount;
        }
        return;
    }

    public function hitheal(Player $player) {
        $hp = $this->getHitHealHp($player->getName());
        $mp = $this->getHitHealMp($player->getName());
        if ($hp < 0)
            $hp = 0;
        if ($mp < 0)
            $mp = 0;
        $player->heal(new EntityRegainHealthEvent($player, $hp, 3));
        $this->addMp($player->getName(), $mp);
    }

    public function getHitHealHp(string $name) {
        $value = $this->plugin->data [$name] ["Stat_HitHealHp"] + $this->plugin->data [$name] ["Skill_HitHealHp"] + $this->plugin->data [$name] ["EquipmentRing_HitHealHp"] + $this->plugin->data [$name] ["EquipmentPendant_HitHealHp"];
        if ($this->getHitHealHpPer($name) < 0)
            return $value - ($value * abs($this->getHitHealHpPer($name)) / 100);
        elseif ($this->getHitHealHpPer($name) > 0)
            return $value + ($value * abs($this->getHitHealHpPer($name)) / 100);
        return $value;
    }

    public function getHitHealHpPer($name) {
        return $this->plugin->data [$name] ["Stat_HitHealHp_Per"] + $this->plugin->data [$name] ["Skill_HitHealHp_Per"] + $this->plugin->data [$name] ["EquipmentRing_HitHealHp_Per"] + $this->plugin->data [$name] ["EquipmentPendant_HitHealHp_Per"];
    }

    public function getHitHealMp(string $name) {
        $value = 10 + $this->plugin->data [$name] ["Stat_HitHealMp"] + $this->plugin->data [$name] ["Skill_HitHealMp"] + $this->plugin->data [$name] ["EquipmentRing_HitHealMp"] + $this->plugin->data [$name] ["EquipmentPendant_HitHealMp"];
        if ($this->getHitHealMpPer($name) < 0)
            return $value - ($value * abs($this->getHitHealMpPer($name)) / 100);
        elseif ($this->getHitHealMpPer($name) > 0)
            return $value + ($value * abs($this->getHitHealMpPer($name)) / 100);
        return $value;
    }

    public function getHitHealMpPer($name) {
        return $this->plugin->data [$name] ["Stat_HitHealMp_Per"] + $this->plugin->data [$name] ["Skill_HitHealMp_Per"] + $this->plugin->data [$name] ["EquipmentRing_HitHealMp_Per"] + $this->plugin->data [$name] ["EquipmentPendant_HitHealMp_Per"];
    }

    /*public function HealthBar(Player $player){
        $maxhp = $player->getMaxHealth();
        $hp = $player->getHealth();
        $o = $maxhp / 27;
        if($o == 0) return "로딩중...";
        if($maxhp == $hp){
            $a = str_repeat("§c❙§r", round($maxhp / $o));
            return $a;
        }elseif($maxhp - $hp > 0){
            $a = str_repeat("§c❙§r", round($hp / $o)).str_repeat("§0❙§r", round($maxhp / $o - $hp / $o));
            return $a;
        }
    }

    public function ManaBar(Player $player){
        $name = $player->getName();
        $maxmp = $this->getMaxMp($name);
        $mp = $this->getMp($name);
        $o = $maxmp / 27;
        if($o == 0) return "로딩중...";
        if($maxmp == $mp){
            $a = str_repeat("§9❙§r", round($maxmp / $o));
            return $a;
        }elseif($maxmp - $mp > 0){
            $a = str_repeat("§0❙§r", round($maxmp / $o - $mp / $o)).str_repeat("§9❙§r", round($mp / $o));
            return $a;
        }
    }*/

    public function HealthBar(Player $player) {
        $maxhp = $player->getMaxHealth();
        $hp = $player->getHealth();
        $o = $maxhp / 40;
        if ($o == 0) return "로딩중...";
        if ($maxhp == $hp) {
            $a = str_repeat("§c⎪§r", round($maxhp / $o));
            return $a . "";
        } elseif ($maxhp - $hp > 0) {
            $a = str_repeat("§c⎪§r", round($hp / $o)) . str_repeat("§0⎪§r", round($maxhp / $o - $hp / $o));
            return $a;
        }
    }

    public function ManaBar(Player $player) {
        $name = $player->getName();
        $maxmp = $this->getMaxMp($name);
        $mp = $this->getMp($name);
        $o = $maxmp / 40;
        if ($o == 0) return "로딩중...";
        if ($maxmp == $mp) {
            $a = str_repeat("§9⎪§r", round($maxmp / $o));
            return $a;
        } elseif ($maxmp - $mp > 0) {
            $a = str_repeat("§0⎪§r", round($maxmp / $o - $mp / $o)) . str_repeat("§9⎪§r", round($mp / $o));
            return $a;
        }
    }

    public function ExpBar(Player $player) {
        $name = $player->getName();
        $maxexp = $this->getMaxExp($name);
        $exp = $this->getExp($name);
        $o = $maxexp / 50;
        if ($o == 0) return "로딩중...";
        if ($maxexp == $exp) {
            $a = str_repeat("§l§a|§r", round($maxexp / $o));
            return $a;
        } elseif ($maxexp - $exp > 0) {
            $a = str_repeat("§l§a|§r", round($exp / $o)) . str_repeat("§l§0|§r", round($maxexp / $o - $exp / $o));
            return $a;
        }
    }
}
