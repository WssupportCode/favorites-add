<?php

namespace WS\Helpers\Catalog;

use Bitrix\Main\Loader;
use Bitrix\Sale\Fuser;
use WS\Base;

/**
 * Class Favorite
 * @package WS\Helpers\Catalog
 */
class Favorite extends Base
{
    /**
     * @param $prodId
     * @return array|false
     */
    public function addToFavorite($prodId)
    {
        self::initModules();
        if (!$prodId) {
            return false;
        }
        $arFields = [
            "NAME" => "Избранное",
            "IBLOCK_ID" => self::getIblockId(),
            "PROPERTY_VALUES" => [
                "FUSER_ID" => self::getFuserId(),
                "PRODUCT" => $prodId
            ]
        ];
        $el = new \CIBlockElement();
        return [
            "ACTION" => "ADD",
            "STATUS" => (bool)$el->Add($arFields)
        ];
    }

    /**
     * @param $prodId
     * @return array|false
     */
    public function deleteFromFavorite($prodId)
    {
        self::initModules();
        if (!$prodId) {
            return false;
        }
        $arProd = $this->getFavorites(["PROPERTY_PRODUCT" => $prodId])[0];
        return [
            "ACTION" => "DELETE",
            "STATUS" => \CIBlockElement::Delete($arProd["ID"])
        ];
    }

    /**
     * @param array $addFilter
     * @return array
     */
    public function getFavorites($addFilter = [])
    {
        self::initModules();
        $arFavorites = [];
        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => self::getIblockId(),
            "PROPERTY_FUSER_ID" => self::getFuserId()
        ];
        if (!empty($addFilter)) {
            $arFilter = array_merge($addFilter, $arFilter);
        }
        $arSelect = ["ID", "IBLOCK_ID", "PROPERTY_FUSER_ID", "PROPERTY_PRODUCT"];
        $dbRes = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($arRes = $dbRes->Fetch()) {
            $arRes = self::prepareArRes($arRes);
            $arFavorites[] = $arRes;
        }
        return $arFavorites;
    }

    /**
     * @param $prodId
     * @return bool
     */
    public function checkProductInFavorite($prodId)
    {
        return !empty($this->getFavorites(["PROPERTY_PRODUCT" => $prodId]));
    }


    /**
     * @param array $arRes
     * @return array
     */
    private static function prepareArRes(array $arRes)
    {
        $arRes = [
            "PRODUCT_ID" => $arRes['PROPERTY_PRODUCT_VALUE'],
            "FUSER_ID" => $arRes["PROPERTY_FUSER_ID_VALUE"],
            "ID" => $arRes["ID"],
            "IBLOCK_ID" => $arRes["IBLOCK_ID"],
        ];
        return $arRes;
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    private static function initModules()
    {
        Loader::includeModule("iblock");
        Loader::includeModule("sale");
        Loader::includeModule("ws.projectsettings");
    }

    /**
     * @return string
     */
    private function getIblockId()
    {
        return \WS_PSettings::getFieldValue("FAVORITES_IBLOCK_ID");
    }

    /**
     * @return false|int|null
     */
    private function getFuserId()
    {
        return Fuser::getId();
    }
}