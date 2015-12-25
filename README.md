# Bitrix echo `<pre>` (BxEcho)
---
Модуль для 1С-Битрикс. Позволяет быстро, просто и удобно выводить данные в браузер, в консоль, в файл.

## Описание
Модуль написан с использованием нового ядра Bitrix d7. Позволяет быстро и гибко управлять выводом переменных/данных для
отладки/анализа. Имеет гибкую систему настройки условий, при которых будет производится вывод (проверка параметров,
проверка прав, проверка групп пользователей). BxEcho написан таким образом, что для настройки его настройки не требуется ничего,
для кастомизации предусмотренны все возможные варианты (соблюдается принцип наследования). Файл конфигурации позволяет избежать
неудобных для разработчика GUI настроек, но в то же время для настроек используется простой формат YAML (понятный любому).

## Установка
- Скопировать репозиторий в папку `/bitrix/modules/` или `/local/modules/`
- Зайти в административную часть сайта и установить модуль в системе 1С Bitrix

## Использование

#### Использование `bxe()`

```php
<?php 
/*Вывод переменной $arResult на экран*/
bxe($arResult);

/*Вывод переменной $arResult на экран, с подписью*/
bxe($arResult, 'result');

/*Вывод переменной $arResult на экран, с подписью. не использовать декоративный вывод*/
bxe($arResult, 'result', ['prettyFormat' => false]);

/*Вывод переменной $arResult на экран, с подписью. Выводить только данные (все остальное содержимое буфера отбрасывается)*/
bxe($arResult, 'result', ['debugOnly' => true]);

/*Вывод переменной $arResult на экран, с подписью. Выводить данные используя функцию var_dump (по умолчанию print_r)*/
bxe($arResult, 'result', ['printFunction' => 'var_dump']);

/*Вывод переменной $arResult на экран, с подписью. Используя для проверки объект класса Shantilab\BxEcho\Checker\Checker для проверки прав на вывод*/
use Shantilab\BxEcho\Checker\Checker;
$checker = new Checker();
bxe($arResult, 'result', null, $checker);

/*Вывод переменной $arResult на экран, с подписью. Возвращает объект класса BxEcho*/
$bxe = bxe($arResult, 'result', null, null, true);
$bxe->show(); //вывод

//Вывод в консоль, в файл, на экран
$bxe = bxe($arResult, 'result', null, null, true);
$bxe->toScreen()->toConsole()->toFile();
```
### Функция `bxe()`
Функция обёртка над классом `BxEcho` для более удобного использования. Всегда возвращает объект класса `BxEcho`.
```php
<?php 
/**
 * @param null $var
 * @param null $name
 * @param array $options
 * @param CheckerInterface|null $checker
 * @param bool|true $showImmidiatly
 * @return BxEcho|void
 */
function bxe($var = null, $name = null, $options = [], Shantilab\BxEcho\Checker\CheckerInterface $checker = null, $showImmidiatly = true)
```
Переменная      | Тип           | Значение                | Поведение
--------------- | ------------- | ----------------------- | ------------
$var            | mixed         | переменная              | Вывод переменной
$name           | string        | подпись для переменной  | Вывод переменной с подписью
$options        | array         | опци для вывода         | Вывод с учётом параметров
$checker        | Checker       | объект типа Checker     | Вывод с учетом проверки условий конкретного объекта Checker
$showImmidiatly | bool          | true/false              | Срабатывание метода BxEcho->show()

#### Опции (`$options`)

Вывод на экран

Ключ            | Значения      | По умолчанию            | Поведение
--------------- | ------------- | ----------------------- | ------------
prettyFormat    | true/false    | true                    | Использовать декоративный вывод
debugOnly       | true/false    | false                   | Вывод только переменной
printFunction   | function      | print_r                 | Функция для вывода

Вывод в файл

Ключ            | Значения      | По умолчанию            | Поведение
--------------- | ------------- | ----------------------- | ------------
type            | write/dump      | write                 | Использовать декоративный вывод
path            | путь к файлу    | '/../bx_log.log'      | Вывод только переменной
append          | true/false      | true                  | Функция для вывода
prettyFormat    | true/false      | true                  | Функция для вывода


## Опции
Все опции описаны в файле `settings.yaml`. Эти опции применяются по умолчанию.

Переменная      | Значение           | Значение по умолчанию                | Описание
--------------- | ------------------ | ------------------------------------ | ------------
classBindings           |
Checker                 | string       | 'Shantilab\BxEcho\Checker\Checker'                 | Класс для проверки прав
DisplayPrinter          | string       | 'Shantilab\BxEcho\Printer\DisplayPrinter'          | Класс для вывода на экран
DisplayPrinterDecorator | string       | 'Shantilab\BxEcho\Printer\DisplayPrinterDecorator' | Класс для декоративного вывода на экран
ConsolePrinter          | string       | 'Shantilab\BxEcho\Printer\ConsolePrinter'          | Класс для вывода в консоль
FilePrinter             | string       | 'Shantilab\BxEcho\Printer\FilePrinter'             | Класс для вывода в файл
FilePrinterDecorator    | string       | 'Shantilab\BxEcho\Printer\FilePrinterDecorator'    | Класс для декоративного вывода в файл
Checker                 |
userIds                 | array       | null                 | Id пользователей, для просмотра вывода
userGroups              | array       | null                 | Id групп, для просмотра вывода
get                     | array       | null                 | Ключи `$_GET` переменной, для просмотра вывода
post                    | array       | null                 | Ключи `$_POST` переменной, для просмотра вывода
request                 | array       | null                 | Ключи `$_REQUEST` переменной, для просмотра вывода
cookies                 | array       | null                 | Просмотр вывода при установленных куках
sessIds                 | array       | null                 | Id сессий, для просмотра вывода
session                 | array       | null                 | Ключи `$_SESSION` переменной, для просмотра вывода
url                     | urlString   | null                 | URL, при котором будут выводится данные
isPost                  | bool        | null                 | POST-запрос
isGet                   | bool        | null                 | GET-запрос
isAjax                  | bool        | null                 | AJAX-запрос
isAdmin                 | bool        | true                 | Пользователь принадлежит группе администраторы


