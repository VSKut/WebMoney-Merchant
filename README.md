# О библиотеке WebMoney Merchant

Эта библиотека должна помочь вам в работе с интерфейсом WebMoney Merchant: https://wiki.webmoney.ru/projects/webmoney/wiki/Web_Merchant_Interface

# Примеры

## Генерация формы

Пример: https://github.com/VSKut/WebMoney-Merchant/examples/form.php

**Подключаем класс любым доступным образом**
```php
require_once  '../src/WMMerchantForm.class.php';
```

**Передаём кошелёк продавца**
```php

$merchantForm = new WMMerchantForm('R000000000000');
```

**Устанавливаем сумму для оплаты**
```php
$merchantForm->setAmount(123.45);
```

**Устанавливаем описание платежа**
```php
$merchantForm->setDescription('Это описание платежа');
```

**Устанавливаем номер платежа**
```php
$merchantForm->setPaymentNumber(12);
```

**Устанавливаем дополнительные параметры** (не обязательно)
```php
$merchantForm->setCustom(array(
    'USER_ID' => 15,
    'ITEM_ID' => 24,
    'CATEGORY_ID' => 1,
));
```

**Передаём RESULT Url посредством формы** (не обязательно)

<small>Для использования своих URL через форму не забудьте выставить в настройках кошелька галочку на "Позволять использовать URL, передаваемые в форме"</small>
```php
$merchantForm->setResultUrl('http://vskut.ru/result.php');
```

**Передаём SUCCESS Url и тип вызова посредством формы** (не обязательно)

<small>Для использования своих URL через форму не забудьте выставить в настройках кошелька галочку на "Позволять использовать URL, передаваемые в форме"</small>
```php
$merchantForm->setSuccessUrl('http://vskut.ru/success.php', 2);
/*
 * * Типы вызова:
 * 0 - GET
 * 1 - POST
 * 2 - URL
 * */
```

**Передаём FAIL Url и тип вызова посредством формы** (не обязательно)

<small>Для использования своих URL через форму не забудьте выставить в настройках кошелька галочку на "Позволять использовать URL, передаваемые в форме"</small>
```php
$merchantForm->setFailUrl('http://vskut.ru/fail.php', 2);
/*
 * * Типы вызова:
 * 0 - GET
 * 1 - POST
 * 2 - URL
 * */

```

**В конец формы добавляем JS скрипт с автоматической отправкой формы** (не обязательно)
```php
$merchantForm->setAutoSendForm();
```

**Получаем HTML сгенерированной формы**
```php
$html = $merchantForm->getHTML();
```

**Just do it :)**
```php
echo $html;
```


## RESULT обработчик

Пример: https://github.com/VSKut/WebMoney-Merchant/examples/result.php

**Подключаем класс любым доступным образом**
```php
require_once  '../src/WMMerchantCallback.class.php';
```

**Передаём кошелёк продавца, секретный ключ, POST данные**
```php

$merchantCallback = new WMMerchantCallback('R000000000000', '000000000000000', $_POST);
```


**Устанавливаем стоимость** (не обязательно)

<small>Если не установлено, то стоимость проверяться не будет и скрипт пропустит все платежи вне зависимости от суммы</small>
```php
$merchantCallback->setAmount(123.45);
```

**Проверяем данные запроса на валидность**
```php
if ($merchantCallback->isInvalidData()) {

    echo $merchantCallback->getError();
    exit();

}
```

**Проверяем тип запроса** (pre-request или finally-request)
```php
if ($merchantCallback->isPreRequest()) {

    /*
     * * Пришёл предварительный запрос, деньги от пользователя ещё не переведены
     * 1) Если нужно, то выполняем любые иные проверки
     * 2) Возвращаем успешный ответ "YES" и тем самым разрешаем выполнить запрос
     * */
     
    echo 'YES';

} else {

    /*
     * * Пришёл окончательный запрос, деньги уже у нас на кошельке
     * 1) Если нужно, то выполняем любые иные проверки
     * 2) Производим выдачу товара
     * */

}
```