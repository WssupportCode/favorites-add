<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use WS\Helpers\Catalog\Favorite;

if (!Loader::includeModule("sale")
    || !Loader::includeModule("catalog")
    || !Loader::includeModule("iblock")) {
    echo "failure";
    return;
}

$request = Application::getInstance()->getContext()->getRequest();
$favorite = new Favorite();
$prodId = trim($request->get("item"));
if ($request->isPost() && $prodId) {
    if (!$favorite->checkProductInFavorite($prodId)) {
        $result = $favorite->addToFavorite($prodId);
    } else {
        $result = $favorite->deleteFromFavorite($prodId);
    }

    echo json_encode($result);
}