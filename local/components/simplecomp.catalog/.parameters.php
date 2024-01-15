<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "PRODUCTS_IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLECOMP_PRODUCT_IBLOCK_ID"),
            "TYPE" => "STRING"
        ],
        "NEWS_IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLECOMP_NEWS_IBLOCK_ID"),
            "TYPE" => "STRING"
        ],
        "PRODUCTS_IBLOCK_ID_PROP" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("SIMPLECOMP_ID_USER_PROP_PRODUCT_SECT"),
            "TYPE" => "STRING"
        ],
        "CACHE_TIME"  =>  ["DEFAULT" => 36000000],
    ]
];