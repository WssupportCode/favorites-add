<?php

use Bitrix\Main\Loader;
use WS\ReduceMigrations\Builder\Entity\Iblock;
use WS\ReduceMigrations\Builder\IblockBuilder;

/**
 * Class definition update migrations scenario actions
 **/
class ws_m_1612947664_sozdanie_ib_izbrannoe extends \WS\ReduceMigrations\Scenario\ScriptScenario
{

    /**
     * Name of scenario
     **/
    static public function name()
    {
        return "Создание ИБ \"Избранное\"";
    }

    /**
     * Priority of scenario
     **/
    static public function priority()
    {
        return self::PRIORITY_HIGH;
    }

    /**
     * @return string hash
     */
    static public function hash()
    {
        return "6177274e1db5d7e2b229e6608f1e438119707be6";
    }

    /**
     * @return int approximately time in seconds
     */
    static public function approximatelyTime()
    {
        return 0;
    }

    /**
     * Write action by apply scenario. Use method `setData` for save need rollback data
     **/
    public function commit()
    {
        Loader::includeModule("ws.projectsettings");
        $builder = new IblockBuilder();
        $iblock = $builder->createIblock('service', 'Избранное', function (Iblock $iblock) {
            $iblock
                ->siteId('s1')
                ->sort(100)
                ->code('favorite')
                ->groupId(['2' => 'R', '5' => 'X']);

            $iblock
                ->addProperty('FUSER ID')
                ->code('FUSER_ID')
                ->typeString()
                ->required()
                ->sort(100);

            $iblock
                ->addProperty('Товар')
                ->code('PRODUCT')
                ->required()
                ->typeElement("CATALOG_IBLOCK_ID"); /** Тут необходимо указать свой ID инфоблока каталога */
        });

        $this->setData(["IBLOCK_ID" => $iblock->getId()]);
        WS_PSettings::setupField([
            "label" => "ID инфоблока избранного",
            "name" => "FAVORITES_IBLOCK_ID",
            "value" => $iblock->getId(),
            "type" => "iblock"
        ]);
    }

    /**
     * Write action by rollback scenario. Use method `getData` for getting commit saved data
     **/
    public function rollback()
    {
        Loader::includeModule("ws.projectsettings");
        $data = $this->getData();
        $builder = new IblockBuilder();
        $builder->updateIblock($data["IBLOCK_ID"], function (Iblock $iblock) {
            $iblock->deleteProperty("FUSER ID");
            $iblock->deleteProperty("Товар");
        });

        $builder->removeIblockById($data["IBLOCK_ID"]);
        WS_PSettings::clearField("FAVORITES_IBLOCK_ID");
    }
}