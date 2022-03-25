# LaraTools
A kit of frequently used tools

## Install
`composer require archee-nic/laratools`

## Classes
| File                                         | Target                     |
|----------------------------------------------|----------------------------|
| ArcheeNic\LaraTools\Helper\JsonOrderedHelper | Json decode ordered helper |
| ArcheeNic\LaraTools\CliColor                 | Console Colors             |
| ArcheeNic\LaraTools\toolsPgMigration         | Postgres Migration Helpers |


## Что нового в версии 3

### Совместима только с PHP 8+ 
Библиотека приведена к синтаксису версии php 8

### Классы которые помечены устаревшими
* ArcheeNic\LaraTools\EnumType
* ArcheeNic\LaraTools\MainDataObjectF
* ArcheeNic\LaraTools\MainDataObjectFTyped
* ArcheeNic\LaraTools\MainDataObjectS
* ArcheeNic\LaraTools\ModelPgArray

### Переименован класс 
* с `ArcheeNic\LaraTools\сliColor` на `ArcheeNic\LaraTools\CliColor`
* с `ArcheeNic\LaraTools\toolsMigration` на `ArcheeNic\LaraTools\toolsPgMigration`

## Обновление версии с 2 до 3
### Шаг №1
Используемый класс `ArcheeNic\LaraTools\toolsMigration` заменить на `ArcheeNic\LaraTools\toolsPgMigration`

### Шаг №2
Отказаться от устаревших методов:
* `ArcheeNic\LaraTools\toolsPgMigration::createForeignFieldInteger`
* `ArcheeNic\LaraTools\toolsPgMigration::dropIndexByPg`
* `ArcheeNic\LaraTools\toolsPgMigration::createIndexByPg`
* `ArcheeNic\LaraTools\toolsPgMigration::createGinIndex`
* `ArcheeNic\LaraTools\toolsPgMigration::dropGinIndex`
* `ArcheeNic\LaraTools\toolsPgMigration::addFieldComment`
