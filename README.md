# О библиотеке WebMoney Merchant

Эта библиотека должна помочь вам в работе с интерфейсом WebMoney Merchant: https://wiki.webmoney.ru/projects/webmoney/wiki/Web_Merchant_Interface


### Навигация

1. [О библиотеке WebMoney Merchant](https://github.com/VSKut/WebMoney-Merchant#О-библиотеке-webmoney-merchant)
    * [Навигация](https://github.com/VSKut/WebMoney-Merchant#Навигация)
2. [Установка через Composer](https://github.com/VSKut/WebMoney-Merchant#Установка-через-composer)
2. [Использование](https://github.com/VSKut/WebMoney-Merchant#Использование)
    * [Генерация формы](https://github.com/VSKut/WebMoney-Merchant#Генерация-формы)
    * [RESULT обработчик](https://github.com/VSKut/WebMoney-Merchant#result-обработчик)
3. [Настройка WebMoney Merchant](https://github.com/VSKut/WebMoney-Merchant#Настройка-webmoney-merchant)
    * [Переходим к настройке кошелька](https://github.com/VSKut/WebMoney-Merchant#Переходим-к-настройке-кошелька)
    * [Настройка кошелька](https://github.com/VSKut/WebMoney-Merchant#Настройка-кошелька)

# Установка через Composer
1. Устанавливаем [Composer](http://getcomposer.org/):

    ```
    curl -sS https://getcomposer.org/installer | php
    ```

2. Добавляем WebMoney Merchant в зависимость:

    ```
    php composer.phar require vskut/webmoney-merchant:*@dev
    ```

# Использование

### Генерация формы

Пример: https://github.com/VSKut/WebMoney-Merchant/blob/master/examples/form.php

**Подключаем класс любым доступным образом**

Native:
```php
require_once('../src/WMMerchantForm.class.php');
```

Composer:
```php
require_once(__DIR__.'/vendor/autoload.php');
```

**Используем пространство имён**
```php
use VSKut\WebMoney_Merchant\WMMerchantForm;
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
$merchantForm->setDataCustom(array(
    'USER_ID' => 15,
    'ITEM_ID' => 24,
    'CATEGORY_ID' => 1,
));
```

**Передаём RESULT Url посредством формы** (не обязательно)
> Для использования своих URL через форму не забудьте выставить в настройках кошелька галочку на "Позволять использовать URL, передаваемые в форме"

```php
$merchantForm->setResultUrl('http://vskut.ru/result.php');
```

**Передаём SUCCESS Url и тип вызова посредством формы** (не обязательно)

> Для использования своих URL через форму не забудьте выставить в настройках кошелька галочку на "Позволять использовать URL, передаваемые в форме"

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

> Для использования своих URL через форму не забудьте выставить в настройках кошелька галочку на "Позволять использовать URL, передаваемые в форме"

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


### RESULT обработчик

Пример: https://github.com/VSKut/WebMoney-Merchant/blob/master/examples/result.php

**Подключаем класс любым доступным образом**

Native:
```php
require_once('../src/WMMerchantCallback.class.php');
```

Composer:
```php
require_once(__DIR__.'/vendor/autoload.php');
```


**Используем пространство имён**
```php
use VSKut\WebMoney_Merchant\WMMerchantCallback;
```


**Передаём кошелёк продавца, секретный ключ, POST данные**
```php
$merchantCallback = new WMMerchantCallback('R000000000000', '000000000000000', $_POST);
```


**Устанавливаем стоимость** (не обязательно)

> Если не установлено, то стоимость проверяться не будет и скрипт пропустит все платежи вне зависимости от суммы

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
```

# Настройка WebMoney Merchant

### Переходим к настройке кошелька
1. Переходим на https://merchant.webmoney.ru
2. Проходим авторизацию
3. Идём в настройки https://merchant.webmoney.ru/conf/purses.asp
4. Напротив нужного кошелька выбираем `настроить`

### Настройка кошелька
1. Указываем `тестовый` либо `рабочий` режим работы
2. Указываем торговое имя
3. Указываем `Secret Key`
4. Указываем `Result URL`
5. Выбираем check-box [x] `Передавать параметры в предварительном запросе`
6. Указываем `Success URL` и метод его вызова
7. Указываем `Fail URL` и метод его вызова
8. При необходимости выбираем check-box [x] `Позволять использовать URL, передаваемые в форме`
9. Выбираем в `Метод формирования контрольной подписи` - `SHA256`
10. Сохраняем :)