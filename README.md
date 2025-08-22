## WB-api
## Функционал
- Получение данных по эндпоинтам:
    - /api/stocks
    - /api/sales
    - /api/orders
    - /api/incomes
- Поддержка параметров:
    - dateFrom (обязательный, Y-m-d)
    - dateTo (опциональный, кроме orders)
    - page, limit
- Данные сохраняются в таблицу wb_raw.
- Консольная команда для импорта в бд: php artisan wb:fetch.

## БД
 - db4free.net
 - phpMyAdmin
 - пользователь: freshest_pumpkin
 - пароль: kirill23143
 - база данных: wb_api
 - таблица: wb_raw
