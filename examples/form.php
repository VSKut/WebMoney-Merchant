<?php
require_once('../src/WMMerchantForm.class.php');

// Используем пространство имён
use VSKut\WebMoney_Merchant\WMMerchantForm;

// Передаём кошелёк продавца
$merchantForm = new WMMerchantForm('R000000000000');


// Устанавливаем сумму для оплаты
$merchantForm->setAmount(123.45);


// Устанавливаем описание платежа
$merchantForm->setDescription('Это описание платежа');


// Устанавливаем номер платежа
$merchantForm->setPaymentNumber(12);


// Устанавливаем дополнительные параметры (не обязательно)
$merchantForm->setCustom(array(
    'USER_ID' => 15,
    'ITEM_ID' => 24,
    'CATEGORY_ID' => 1,
));


// Передаём RESULT Url посредством формы (не обязательно)
$merchantForm->setResultUrl('http://vskut.ru/result.php');


// Передаём SUCCESS Url и тип вызова посредством формы (не обязательно)
$merchantForm->setSuccessUrl('http://vskut.ru/success.php', 2);


// Передаём FAIL Url и тип вызова посредством формы (не обязательно)
$merchantForm->setFailUrl('http://vskut.ru/fail.php', 2);
/*
 * * Типы вызова:
 * 0 - GET
 * 1 - POST
 * 2 - URL
 *
 * * Для использования своих URL через форму не забудьте выставить в настройках кошелька галочку на "Позволять использовать URL, передаваемые в форме"
 *
 * */


// В конец формы добавляем JS скрипт с автоматической отправкой формы (не обязательно)
$merchantForm->setAutoSendForm();


// Получаем HTML сгенерированной формы
$html = $merchantForm->getHTML();


// Just do it :)
echo $html;