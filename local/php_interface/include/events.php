<?php

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();
$eventManager->addEventHandler("main", "OnBeforeEventAdd", [OnBeforeEventAddHandlers::class, "onBeforeEventAddHandler"]);

class OnBeforeEventAddHandlers
{
    public static function onBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        global $USER;
        if($event !== "FEEDBACK_FORM") {
            return true;
        }
        if ($USER->IsAuthorized()) {
            $arFields['AUTHOR'] = GetMessage("USER_AUTH", [
                "#ID#" => $USER->GetID(),
                "#LOGIN#" => $USER->GetLogin(),
                "#NAME#" => $USER->GetFullName(),
                "#DORM_DATA#" => $arFields['AUTHOR']
            ]);
        } else {
            $arFields['AUTHOR'] = GetMessage("USER_NOT_AUTH", ["#DORM_DATA#" => $arFields['AUTHOR']]);
        }
        CEventLog::Add([
            "SEVERITY" => "SECURITY",
            "AUDIT_TYPE_ID" => GetMessage("REPLACE_DATA"),
            "MODULE_ID" => "main",
            "ITEM_ID" => $event,
            "DESCRIPTION" => GetMessage("REPLACE_DATA") . "–" . $arFields['AUTHOR']
        ]);
    }
}