<?php

namespace Core;

use Core\util\InfoTask;
use Core\util\Util;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Attribute;
use pocketmine\entity\AttributeMap;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScoreboardIdentityPacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScoreboardIdentityPacketEntry;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use SkillManager\SkillManager;
use Status\Status;
use TeleCash\TeleCash;
use TeleMoney\TeleMoney;

class Core extends PluginBase implements Listener {
    private static $instance = null;
    public $config, $data;
    //public $pre = "§l§e[ §f시스템 §e]§r§e";
    public $pre = "§e•";
    public $cool = [];
    public $war = [];
    public $time;

    public static function getInstance() {
        return self::$instance;
    }

    public function onLoad() {
        self::$instance = $this;
    }

    public function onEnable() {
        @mkdir($this->getDataFolder());
        $this->saveResource("Info.yml");
        $this->saveResource("User.yml");
        $this->saveResource("Knight.yml");
        $this->saveResource("Archer.yml");
        $this->saveResource("Wizard.yml");
        $this->saveResource("Priest.yml");
        $this->saveResource("MaxExp.yml");
        $this->config = new Config ($this->getDataFolder() . "data.yml", Config::YAML);
        $this->data = $this->config->getAll();
        $this->user = (new Config($this->getDataFolder() . 'User.yml', Config::YAML))->getAll();
        $this->knight = (new Config($this->getDataFolder() . 'Knight.yml', Config::YAML))->getAll();
        $this->archer = (new Config($this->getDataFolder() . 'Archer.yml', Config::YAML))->getAll();
        $this->wizard = (new Config($this->getDataFolder() . 'Wizard.yml', Config::YAML))->getAll();
        $this->priest = (new Config($this->getDataFolder() . 'Priest.yml', Config::YAML))->getAll();
        $this->maxexp = (new Config($this->getDataFolder() . 'MaxExp.yml', Config::YAML))->getAll();
        $this->info = (new Config($this->getDataFolder() . 'Info.yml', Config::YAML))->getAll();
        $this->getScheduler()->scheduleRepeatingTask(new InfoTask($this), 5);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->util = new Util($this);
        $this->stat = Status::getInstance();
        $this->money = TeleMoney::getInstance();
        $this->cash = TeleCash::getInstance();
        $this->id = Entity::$entityCount++;
        $this->skill = SkillManager::getInstance();

        $this->load();
        $this->getServer()->getLogger()->notice("{$this->pre} 파일 검사를 시행합니다..");
        $count = 0;
        foreach ($this->data as $key => $value) {
            $count += $this->check($key);
        }
        $this->getServer()->getLogger()->notice("{$this->pre} {$count}개의 오류를 찾아 수정하였습니다.");
        $this->save();
    }

    private function load() {
        $this->data ["테스트"] = [];
        $this->data ["테스트"] ['Level'] = 1;
        $this->data ["테스트"] ['Exp'] = 0;
        $this->data ["테스트"] ['AllExp'] = 0;
        $this->data ["테스트"] ['MaxExp'] = 100;
        $this->data ["테스트"] ['Job'] = "모험가";
        $this->data ["테스트"] ['Hp'] = 100;
        $this->data ["테스트"] ['Mp'] = 100;
        $this->data ["테스트"] ['MaxHp'] = 100;
        $this->data ["테스트"] ['MaxMp'] = 100;
        $this->data ["테스트"] ['ATK'] = 10;
        $this->data ["테스트"] ['DEF'] = 0;
        $this->data ["테스트"] ['MATK'] = 0;
        $this->data ["테스트"] ['MDEF'] = 0;
        $this->data ["테스트"] ['Critical'] = 0;
        $this->data ["테스트"] ['Evasion'] = 0;
        $this->data ["테스트"] ['Attack'] = 1;
        $this->data ["테스트"] ['CD'] = 200;

        $this->data ["테스트"] ['Stat_MaxHp'] = 0;
        $this->data ["테스트"] ['Stat_MaxMp'] = 0;
        $this->data ["테스트"] ['Stat_ATK'] = 0;
        $this->data ["테스트"] ['Stat_DEF'] = 0;
        $this->data ["테스트"] ['Stat_MATK'] = 0;
        $this->data ["테스트"] ['Stat_MDEF'] = 0;
        $this->data ["테스트"] ['Stat_Critical'] = 0;
        $this->data ["테스트"] ['Stat_Evasion'] = 0;
        $this->data ["테스트"] ['Stat_Attack'] = 0;
        $this->data ["테스트"] ['Stat_CD'] = 0;
        $this->data ["테스트"] ["Stat_AutoHealHp"] = 0;
        $this->data ["테스트"] ["Stat_AutoHealMp"] = 0;
        $this->data ["테스트"] ["Stat_HitHealHp"] = 0;
        $this->data ["테스트"] ["Stat_HitHealMp"] = 0;

        $this->data ["테스트"] ['Skill_MaxHp'] = 0;
        $this->data ["테스트"] ['Skill_MaxMp'] = 0;
        $this->data ["테스트"] ['Skill_ATK'] = 0;
        $this->data ["테스트"] ['Skill_DEF'] = 0;
        $this->data ["테스트"] ['Skill_MATK'] = 0;
        $this->data ["테스트"] ['Skill_MDEF'] = 0;
        $this->data ["테스트"] ['Skill_Critical'] = 0;
        $this->data ["테스트"] ['Skill_Evasion'] = 0;
        $this->data ["테스트"] ['Skill_Attack'] = 0;
        $this->data ["테스트"] ['Skill_CD'] = 0;
        $this->data ["테스트"] ["Skill_AutoHealHp"] = 0;
        $this->data ["테스트"] ["Skill_AutoHealMp"] = 0;
        $this->data ["테스트"] ["Skill_HitHealHp"] = 0;
        $this->data ["테스트"] ["Skill_HitHealMp"] = 0;

        $this->data ["테스트"] ["EquipmentRing_ATK"] = 0;
        $this->data ["테스트"] ["EquipmentRing_DEF"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MATK"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MDEF"] = 0;
        $this->data ["테스트"] ["EquipmentRing_Critical"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MaxHp"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MaxMp"] = 0;
        $this->data ["테스트"] ['EquipmentRing_Evasion'] = 0;
        $this->data ["테스트"] ['EquipmentRing_Attack'] = 0;
        $this->data ["테스트"] ['EquipmentRing_CD'] = 0;
        $this->data ["테스트"] ["EquipmentRing_AutoHealHp"] = 0;
        $this->data ["테스트"] ["EquipmentRing_AutoHealMp"] = 0;
        $this->data ["테스트"] ["EquipmentRing_HitHealHp"] = 0;
        $this->data ["테스트"] ["EquipmentRing_HitHealMp"] = 0;

        $this->data ["테스트"] ["EquipmentPendant_ATK"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_DEF"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MATK"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MDEF"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_Critical"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MaxHp"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MaxMp"] = 0;
        $this->data ["테스트"] ['EquipmentPendant_Evasion'] = 0;
        $this->data ["테스트"] ['EquipmentPendant_Attack'] = 0;
        $this->data ["테스트"] ['EquipmentPendant_CD'] = 0;
        $this->data ["테스트"] ["EquipmentPendant_AutoHealHp"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_AutoHealMp"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_HitHealHp"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_HitHealMp"] = 0;

        $this->data ["테스트"] ['Stat_MaxHp_Per'] = 0;
        $this->data ["테스트"] ['Stat_MaxMp_Per'] = 0;
        $this->data ["테스트"] ['Stat_ATK_Per'] = 0;
        $this->data ["테스트"] ['Stat_DEF_Per'] = 0;
        $this->data ["테스트"] ['Stat_MATK_Per'] = 0;
        $this->data ["테스트"] ['Stat_MDEF_Per'] = 0;
        $this->data ["테스트"] ['Stat_Critical_Per'] = 0;
        $this->data ["테스트"] ['Stat_Evasion_Per'] = 0;
        $this->data ["테스트"] ['Stat_Attack_Per'] = 0;
        $this->data ["테스트"] ['Stat_CD_Per'] = 0;
        $this->data ["테스트"] ["Stat_HitHealHp_Per"] = 0;
        $this->data ["테스트"] ["Stat_HitHealMp_Per"] = 0;

        $this->data ["테스트"] ['Skill_MaxHp_Per'] = 0;
        $this->data ["테스트"] ['Skill_MaxMp_Per'] = 0;
        $this->data ["테스트"] ['Skill_ATK_Per'] = 0;
        $this->data ["테스트"] ['Skill_DEF_Per'] = 0;
        $this->data ["테스트"] ['Skill_MATK_Per'] = 0;
        $this->data ["테스트"] ['Skill_MDEF_Per'] = 0;
        $this->data ["테스트"] ['Skill_Critical_Per'] = 0;
        $this->data ["테스트"] ['Skill_Evasion_Per'] = 0;
        $this->data ["테스트"] ['Skill_Attack_Per'] = 0;
        $this->data ["테스트"] ['Skill_CD_Per'] = 0;
        $this->data ["테스트"] ["Skill_HitHealHp_Per"] = 0;
        $this->data ["테스트"] ["Skill_HitHealMp_Per"] = 0;

        $this->data ["테스트"] ["EquipmentRing_ATK_Per"] = 0;
        $this->data ["테스트"] ["EquipmentRing_DEF_Per"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MATK_Per"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MDEF_Per"] = 0;
        $this->data ["테스트"] ["EquipmentRing_Critical_Per"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MaxHp_Per"] = 0;
        $this->data ["테스트"] ["EquipmentRing_MaxMp_Per"] = 0;
        $this->data ["테스트"] ['EquipmentRing_Evasion_Per'] = 0;
        $this->data ["테스트"] ['EquipmentRing_Attack_Per'] = 0;
        $this->data ["테스트"] ['EquipmentRing_CD_Per'] = 0;
        $this->data ["테스트"] ["EquipmentRing_HitHealHp_Per"] = 0;
        $this->data ["테스트"] ["EquipmentRing_HitHealMp_Per"] = 0;

        $this->data ["테스트"] ["EquipmentPendant_ATK_Per"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_DEF_Per"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MATK_Per"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MDEF_Per"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_Critical_Per"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MaxHp_Per"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_MaxMp_Per"] = 0;
        $this->data ["테스트"] ['EquipmentPendant_Evasion_Per'] = 0;
        $this->data ["테스트"] ['EquipmentPendant_Attack_Per'] = 0;
        $this->data ["테스트"] ['EquipmentPendant_CD_Per'] = 0;
        $this->data ["테스트"] ["EquipmentPendant_HitHealHp_Per"] = 0;
        $this->data ["테스트"] ["EquipmentPendant_HitHealMp_Per"] = 0;
    }

    private function check(string $name) {
        $count = 0;
        foreach ($this->data["테스트"] as $key => $value) {
            if (!isset($this->data[$name][$key])) {
                $this->data[$name][$key] = $value;
                $count++;
            }
            continue;
        }
        return $count;
    }

    public function save() {
        $this->config->setAll($this->data);
        $this->config->save();
    }

    public function onDisable() {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $this->data[$player->getName()]["Hp"] = $player->getHealth();
        }
        $this->save();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        $pre = $this->pre;
        if ($command->getName() == "레벨업") {
            $this->util->levelup($sender->getName());
            $sender->sendMessage("{$pre} 레벨업! 레벨: {$this->util->getLevel($sender->getName())} 최대경험치: {$this->util->getMaxExp($sender->getName())}");
            return true;
        }
        if ($command->getName() == "내정보") {
            $sender->sendMessage("{$pre} 레벨: {$this->util->getLevel($sender->getName())}, 경험치: {$this->util->getExp($sender->getName())}, 최대경험치: {$this->util->getMaxExp($sender->getName())}");
            return true;
        }
        if ($command->getName() == "레벨설정") {
            if (!$sender->isOp()) return false;
            $this->util->setLevel($sender->getName(), $args[0]);
            return true;
        }
        if ($command->getName() == "경험치추가") {
            $this->util->addExp($sender->getName(), $args[0]);
            return true;
        }
        if ($command->getName() == "경험치추가1") {
            $this->util->setAllExp($sender->getName(), $args[0]);
            return true;
        }
        if ($command->getName() == "마나추가") {
            $this->util->addMp($sender->getName(), $args[0]);
            return true;
        }
        if ($command->getName() == "마나설정") {
            $this->util->setMp($sender->getName(), $args[0]);
            return true;
        }
        if ($command->getName() == "전직") {
            if (!$sender->isOp()) return false;
            $this->util->setJob_1($sender->getName(), $args[0]);
            return true;
        }
        return true;
    }

    public function onJoin(PlayerJoinEvent $event) {
        $server = Server::getInstance();
        $player = $event->getPlayer();
        $name = $player->getName();
        if (!isset($this->data [$name])) {
            $player->setXpLevel(1);
            $this->data [$name] = [];
            $this->data [$name] ['Level'] = 1;
            $this->data [$name] ['Exp'] = 0;
            $this->data [$name] ['AllExp'] = 0;
            $this->data [$name] ['MaxExp'] = 100;
            $this->data [$name] ['Job'] = "모험가";
            $this->data [$name] ['Hp'] = 100;
            $this->data [$name] ['Mp'] = 100;
            $this->data [$name] ['MaxHp'] = 100;
            $this->data [$name] ['MaxMp'] = 100;
            $this->data [$name] ['ATK'] = 10;
            $this->data [$name] ['DEF'] = 0;
            $this->data [$name] ['MATK'] = 0;
            $this->data [$name] ['MDEF'] = 0;
            $this->data [$name] ['Critical'] = 0;
            $this->data [$name] ['Evasion'] = 0;
            $this->data [$name] ['Attack'] = 1;
            $this->data [$name] ['CD'] = 200;

            $this->data [$name] ['Stat_MaxHp'] = 0;
            $this->data [$name] ['Stat_MaxMp'] = 0;
            $this->data [$name] ['Stat_ATK'] = 0;
            $this->data [$name] ['Stat_DEF'] = 0;
            $this->data [$name] ['Stat_MATK'] = 0;
            $this->data [$name] ['Stat_MDEF'] = 0;
            $this->data [$name] ['Stat_Critical'] = 0;
            $this->data [$name] ['Stat_Evasion'] = 0;
            $this->data [$name] ['Stat_Attack'] = 0;
            $this->data [$name] ['Stat_CD'] = 0;
            $this->data [$name] ["Stat_AutoHealHp"] = 0;
            $this->data [$name] ["Stat_AutoHealMp"] = 0;
            $this->data [$name] ["Stat_HitHealHp"] = 0;
            $this->data [$name] ["Stat_HitHealMp"] = 0;

            $this->data [$name] ['Skill_MaxHp'] = 0;
            $this->data [$name] ['Skill_MaxMp'] = 0;
            $this->data [$name] ['Skill_ATK'] = 0;
            $this->data [$name] ['Skill_DEF'] = 0;
            $this->data [$name] ['Skill_MATK'] = 0;
            $this->data [$name] ['Skill_MDEF'] = 0;
            $this->data [$name] ['Skill_Critical'] = 0;
            $this->data [$name] ['Skill_Evasion'] = 0;
            $this->data [$name] ['Skill_Attack'] = 0;
            $this->data [$name] ['Skill_CD'] = 0;
            $this->data [$name] ["Skill_AutoHealHp"] = 0;
            $this->data [$name] ["Skill_AutoHealMp"] = 0;
            $this->data [$name] ["Skill_HitHealHp"] = 0;
            $this->data [$name] ["Skill_HitHealMp"] = 0;

            $this->data [$name] ["EquipmentRing_ATK"] = 0;
            $this->data [$name] ["EquipmentRing_DEF"] = 0;
            $this->data [$name] ["EquipmentRing_MATK"] = 0;
            $this->data [$name] ["EquipmentRing_MDEF"] = 0;
            $this->data [$name] ["EquipmentRing_Critical"] = 0;
            $this->data [$name] ["EquipmentRing_MaxHp"] = 0;
            $this->data [$name] ["EquipmentRing_MaxMp"] = 0;
            $this->data [$name] ['EquipmentRing_Evasion'] = 0;
            $this->data [$name] ['EquipmentRing_Attack'] = 0;
            $this->data [$name] ['EquipmentRing_CD'] = 0;
            $this->data [$name] ["EquipmentRing_AutoHealHp"] = 0;
            $this->data [$name] ["EquipmentRing_AutoHealMp"] = 0;
            $this->data [$name] ["EquipmentRing_HitHealHp"] = 0;
            $this->data [$name] ["EquipmentRing_HitHealMp"] = 0;

            $this->data [$name] ["EquipmentPendant_ATK"] = 0;
            $this->data [$name] ["EquipmentPendant_DEF"] = 0;
            $this->data [$name] ["EquipmentPendant_MATK"] = 0;
            $this->data [$name] ["EquipmentPendant_MDEF"] = 0;
            $this->data [$name] ["EquipmentPendant_Critical"] = 0;
            $this->data [$name] ["EquipmentPendant_MaxHp"] = 0;
            $this->data [$name] ["EquipmentPendant_MaxMp"] = 0;
            $this->data [$name] ['EquipmentPendant_Evasion'] = 0;
            $this->data [$name] ['EquipmentPendant_Attack'] = 0;
            $this->data [$name] ['EquipmentPendant_CD'] = 0;
            $this->data [$name] ["EquipmentPendant_AutoHealHp"] = 0;
            $this->data [$name] ["EquipmentPendant_AutoHealMp"] = 0;
            $this->data [$name] ["EquipmentPendant_HitHealHp"] = 0;
            $this->data [$name] ["EquipmentPendant_HitHealMp"] = 0;

            $this->data [$name] ['Stat_MaxHp_Per'] = 0;
            $this->data [$name] ['Stat_MaxMp_Per'] = 0;
            $this->data [$name] ['Stat_ATK_Per'] = 0;
            $this->data [$name] ['Stat_DEF_Per'] = 0;
            $this->data [$name] ['Stat_MATK_Per'] = 0;
            $this->data [$name] ['Stat_MDEF_Per'] = 0;
            $this->data [$name] ['Stat_Critical_Per'] = 0;
            $this->data [$name] ['Stat_Evasion_Per'] = 0;
            $this->data [$name] ['Stat_Attack_Per'] = 0;
            $this->data [$name] ['Stat_CD_Per'] = 0;
            $this->data [$name] ["Stat_HitHealHp_Per"] = 0;
            $this->data [$name] ["Stat_HitHealMp_Per"] = 0;

            $this->data [$name] ['Skill_MaxHp_Per'] = 0;
            $this->data [$name] ['Skill_MaxMp_Per'] = 0;
            $this->data [$name] ['Skill_ATK_Per'] = 0;
            $this->data [$name] ['Skill_DEF_Per'] = 0;
            $this->data [$name] ['Skill_MATK_Per'] = 0;
            $this->data [$name] ['Skill_MDEF_Per'] = 0;
            $this->data [$name] ['Skill_Critical_Per'] = 0;
            $this->data [$name] ['Skill_Evasion_Per'] = 0;
            $this->data [$name] ['Skill_Attack_Per'] = 0;
            $this->data [$name] ['Skill_CD_Per'] = 0;
            $this->data [$name] ["Skill_HitHealHp_Per"] = 0;
            $this->data [$name] ["Skill_HitHealMp_Per"] = 0;

            $this->data [$name] ["EquipmentRing_ATK_Per"] = 0;
            $this->data [$name] ["EquipmentRing_DEF_Per"] = 0;
            $this->data [$name] ["EquipmentRing_MATK_Per"] = 0;
            $this->data [$name] ["EquipmentRing_MDEF_Per"] = 0;
            $this->data [$name] ["EquipmentRing_Critical_Per"] = 0;
            $this->data [$name] ["EquipmentRing_MaxHp_Per"] = 0;
            $this->data [$name] ["EquipmentRing_MaxMp_Per"] = 0;
            $this->data [$name] ['EquipmentRing_Evasion_Per'] = 0;
            $this->data [$name] ['EquipmentRing_Attack_Per'] = 0;
            $this->data [$name] ['EquipmentRing_CD_Per'] = 0;
            $this->data [$name] ["EquipmentRing_HitHealHp_Per"] = 0;
            $this->data [$name] ["EquipmentRing_HitHealMp_Per"] = 0;

            $this->data [$name] ["EquipmentPendant_ATK_Per"] = 0;
            $this->data [$name] ["EquipmentPendant_DEF_Per"] = 0;
            $this->data [$name] ["EquipmentPendant_MATK_Per"] = 0;
            $this->data [$name] ["EquipmentPendant_MDEF_Per"] = 0;
            $this->data [$name] ["EquipmentPendant_Critical_Per"] = 0;
            $this->data [$name] ["EquipmentPendant_MaxHp_Per"] = 0;
            $this->data [$name] ["EquipmentPendant_MaxMp_Per"] = 0;
            $this->data [$name] ['EquipmentPendant_Evasion_Per'] = 0;
            $this->data [$name] ['EquipmentPendant_Attack_Per'] = 0;
            $this->data [$name] ['EquipmentPendant_CD_Per'] = 0;
            $this->data [$name] ["EquipmentPendant_HitHealHp_Per"] = 0;
            $this->data [$name] ["EquipmentPendant_HitHealMp_Per"] = 0;
            $this->save();
        }
        $this->util->setInfo($name);
        if ($this->data[$name]["Hp"] >= $this->util->getMaxHp($name))
            $player->setHealth($this->util->getMaxHp($name));
        else
            $player->setHealth($this->data[$name]["Hp"]);
        $this->data[$name]["Hp"] = $player->getHealth();
        $player->setAllowMovementCheats(true);
    }

    public function onQuit(PlayerQuitEvent $ev) {
        $player = $ev->getPlayer();
        $name = $player->getName();
        $this->data[$name]["Hp"] = $player->getHealth();
    }

    public function onHungry(PlayerExhaustEvent $ev) {
        $ev->setCancelled(true);
    }

    public function sendBoard($player) {
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = "Tele";
        $packets[] = clone $pk;

        $pk = new SetDisplayObjectivePacket();
        $pk->objectiveName = "Tele";
        $pk->displayName = "[ 서버 :: RPG ]";
        $pk->sortOrder = 0;
        $pk->criteriaName = "dummy";
        $pk->displaySlot = "sidebar";
        $packets[] = clone $pk;
        $info = $this->getInfo($player);
        $profile = [];
        $eid = $this->id;

        $pk = new SetScorePacket();
        $pk->type = $pk::TYPE_CHANGE;
        foreach ($info as $value) {
            $entry = new ScorePacketEntry();
            $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
            $entry->customName = " " . $value . str_repeat(" ", 4);
            $entry->score = count($profile);
            $entry->scoreboardId = count($profile);
            $entry->objectiveName = "Tele";
            //$entry->entityUniqueId = $eid;
            $profile[] = $entry;
        }
        $pk->entries = $profile;
        $packets[] = clone $pk;

        /*$profile = [];
        $pk = new SetScoreboardIdentityPacket();
  $pk->type = SetScoreboardIdentityPacket::TYPE_REGISTER_IDENTITY;
        foreach($info as $value){
       $entry = new ScoreboardIdentityPacketEntry();
     $entry->scoreboardId = count($profile);
     $entry->entityUniqueId = $eid;
     $profile[] = $entry;
  }
  $pk->entries = $profile;
  $packets[] = clone $pk;*/

        foreach ($packets as $packet) {
            $player->dataPacket($packet);
        }
    }

    public function getInfo($player) {
        $name = $player->getName();
        if (!isset($this->data[$player->getName()])) {
            $info = ["로딩중..."];
        } else {
            $info = [
                    "§6직업: {$this->util->getJob($name)}",
                    "§e돈: {$this->money->getMoney($name)}원",
                    "§b크레딧: {$this->cash->getCash($name)}개",
                    "§c체력: " . (round($player->getHealth() * 100) / 100) . "/{$player->getMaxHealth()}",
                    "§9마나: {$this->util->getMp($name)}/{$this->util->getMaxMp($name)}",
                    "§a경험치: " . round(($this->util->getExp($name) / $this->util->getMaxExp($name)) * 10000) / 100 . "％"
            ];
        }
        return $info;
    }

}
