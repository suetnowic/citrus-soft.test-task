<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

---
<br>
<p style="font-weight: bold">Каталог:</p>

<?php if (count($arResult["NEWS"]) > 0) { ?>
    <ul>
        <?php foreach ($arResult["NEWS"] as $arNews) { ?>
            <li><b><?= $arNews["NAME"]; ?></b> -
                <?= $arNews["ACTIVE_FROM"]; ?>
                <?php if(is_array($arNews["SECTIONS"])) { ?>
                    (<?= implode(", ", $arNews["SECTIONS"]); ?>)
                <?php } else { ?>
                    (<?= $arNews["SECTIONS"] ?>)
                <?php } ?>
            </li>
            <?php if (count($arNews["PRODUCTS"]) > 0) { ?>
                <ul>
                <?php foreach ($arNews["PRODUCTS"] as $arProduct) { ?>
                    <li>
                        <?= $arProduct["NAME"] . ' - ' .
                        $arProduct["PROPERTY_PRICE_VALUE"] . ' - ' .
                        $arProduct["PROPERTY_MATERIAL_VALUE"] . ' - ' .
                        $arProduct["PROPERTY_ARTNUMBER_VALUE"]?>
                    </li>
                <?php } ?>
                </ul>
            <?php }
        } ?>
    </ul>
<?php } ?>
