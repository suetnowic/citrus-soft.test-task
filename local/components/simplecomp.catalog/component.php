<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!Loader::includeModule("iblock")) {
    ShowError(GetMessage("SIMPLECOMP_IBLOCK_MODULE_NONE"));
    return;
}

if (!isset($arParams["PRODUCTS_IBLOCK_ID"])) {
    $arParams["PRODUCTS_IBLOCK_ID"] = 0;
}
if (!isset($arParams["NEWS_IBLOCK_ID"])) {
    $arParams["NEWS_IBLOCK_ID"] = 0;
}

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

if ($this->startResultCache()) {

    $arActiveNews = ElementTable::getList(
        ["filter" =>
            ["ACTIVE" => "Y", "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"]],
            "select" => ["ID", "NAME", "ACTIVE_FROM"]
        ]
    );

    $arNewsId = [];
    $arNews = [];
    while ($news = $arActiveNews->fetch()) {
        $arNewsId[] = $news["ID"];
        $news["ACTIVE_FROM"] = $news["ACTIVE_FROM"]->format("d.m.Y");
        $arNews[$news["ID"]] = $news;
    }

    $arActiveSections = CIBlockSection::GetList(
        [],
        [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
            $arParams["PRODUCTS_IBLOCK_ID_PROP"] => $arNewsId
        ],
        false,
        ["ID", "NAME", "IBLOCK_ID", $arParams["PRODUCTS_IBLOCK_ID_PROP"]],
        false
    );

    $arSectionsCatalogId = [];
    $arSectionsCatalog = [];
    while ($section = $arActiveSections->Fetch()) {
        $arSectionsCatalogId[] = $section["ID"];
        $arSectionsCatalog[$section["ID"]] = $section;
    }

    $arActiveProducts = CIBlockElement::GetList(
        [],
        ["ACTIVE" => "Y", "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"], "SECTION_ID" => $arSectionsCatalogId],
        false,
        false,
        ["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PROPERTY_ARTNUMBER", "PROPERTY_MATERIAL", "PROPERTY_PRICE"]
    );

    $arResult["PRODUCT_COUNT"] = 0;
    while ($arProduct = $arActiveProducts->Fetch()) {
        $arResult["PRODUCT_COUNT"]++;
        foreach ($arSectionsCatalog[$arProduct["IBLOCK_SECTION_ID"]][$arParams["PRODUCTS_IBLOCK_ID_PROP"]] as $newsId) {
            $arNews[$newsId]["PRODUCTS"][] = $arProduct;
        }
    }

    foreach ($arSectionsCatalog as $arSection) {
        foreach ($arSection[$arParams["PRODUCTS_IBLOCK_ID_PROP"]] as $newId) {
            $arNews[$newId]["SECTIONS"][] = $arSection["NAME"];
        }
    }

    $arResult["NEWS"] = $arNews;

    $this->SetResultCacheKeys(["PRODUCT_COUNT"]);

    $this->includeComponentTemplate();
} else {
    $this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage("SIMPLECOMP_TITLE", ["#PRODUCT_COUNT#" => $arResult["PRODUCT_COUNT"]]));