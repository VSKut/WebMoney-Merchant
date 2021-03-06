<?php
require_once('../src/WMMerchantCallback.class.php');

// Используем пространство имён
use VSKut\WebMoney_Merchant\WMMerchantCallback;

// Передаём кошелёк продавца, секретный ключ, POST данные
$merchantCallback = new WMMerchantCallback('R000000000000', '000000000000000', $_POST);


/*
 * Устанавливаем стоимость (не обязательно)
 *
 * * Если не установлено, то стоимость проверяться не будет и скрипт пропустит все платежи вне зависимости от суммы
 * */
$merchantCallback->setAmount(123.45);


// Проверяем данные запроса на валидность
if ($merchantCallback->isInvalidData()) {

    echo $merchantCallback->getError();
    exit();

}


// Проверяем тип запроса (pre-request или finally-request)
if ($merchantCallback->isPreRequest()) {

    /*
     * * Пришёл предварительный запрос, деньги от пользователя ещё не переведены
     * 1) Если нужно, то выполняем любые иные проверки
     * */

    $array = $merchantCallback->getData();

    $array = $merchantCallback->getDataCustom();

    /*
     * 2) Возвращаем успешный ответ "YES" и тем самым разрешаем выполнить запрос
     * */
    echo 'YES';

} else {

    /*
     * * Пришёл окончательный запрос, деньги уже у нас на кошельке
     * 1) Если нужно, то выполняем любые иные проверки
     * */

    $array = $merchantCallback->getData();

    $array = $merchantCallback->getDataCustom();

    /*
     * 2) Производим выдачу товара
     * */

}
