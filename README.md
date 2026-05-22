# THE VOID — Web Store (Project)

Краткое руководство и ссылки по проекту.

## Что внутри репозитория
- `controller/`, `model/`, `view/` — MVC структура проекта.
- `inc/Database.php` — подключение к БД.
- `public/` — статические файлы (css, js).
- `admin/` — административная панель.
- `clothing_shop.sql`, `newsportal.sql` — дампы БД.

## Быстрый старт
1. Положите проект в папку веб-сервера (например, XAMPP `htdocs`).
2. Импортируйте базу данных: `clothing_shop.sql`.
3. Настройте `inc/Database.php`.
4. Запустите проект в браузере.

## Создание .docx с финальной запиской
Рекомендованный способ (требует Pandoc):
```bash
pandoc c/\xampp\htdocs\VOID5\Lopputoo_final.md -o C:\xampp\htdocs\VOID5\Lõpputöö4_filled.docx
```

Если Pandoc не доступен — используйте локальный Python-скрипт (требуется `python-docx`):
```bash
pip install python-docx
python convert_md_to_docx.py Lopputoo_final.md Lõpputöö4_filled.docx
```

Скрипт сохранит маркеры `[[screenshot:...]]` как заметки, которые вы сможете заменить в Word.

## Документация
- Руководство пользователя: `docs/user_guide.md`.
- Черновик финальной записки: `Lopputoo_final.md`.
- Скрипт для конвертации: `convert_md_to_docx.py`.
