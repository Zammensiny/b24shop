<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
if (!check_bitrix_sessid()) return;
?>

<form action="" method="post">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="install" value="Y">

    <p><b><?=Loc::getMessage('IBS_SHOP_WARNING_LABEL')?></b></p>
    <label>
        <input type="checkbox" name="delete_tables" value="Y">
        <?=Loc::getMessage('IBS_SHOP_CHECKBOX_LABEL')?>
    </label>
    <br><br>

    <input type="submit" value="<?=Loc::getMessage('IBS_SHOP_BUTTON_LABEL')?>">
</form>