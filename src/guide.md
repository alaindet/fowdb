# Conventions and how-tos on FoWDB

# Routes

- Resource = card
- Controller: CardsController
- `{id}` can be anything unique, not necessarily an incremental ID
- `cards/manage` lists cards and shows action buttons (write access)
- `cards/search` shows a search form to get a filtered list of cards (read access)

| Method    | URL               | Controller action |
| --------- | ----------------- | ------------------|
| GET       | cards/create      | @createForm       |
| POST      | cards             | @create           |
| GET       | cards/manage      | @indexManage      |
| GET       | cards/search      | @indexForm        |
| GET       | cards             | @index            |
| GET       | cards/{id}        | @show             |
| GET       | cards/update/{id} | @updateForm       |
| PUT/PATCH | cards/{id}        | @update           |
| GET       | cards/delete      | @deleteForm       |
| DELETE    | cards             | @delete           |


# Compiling assets

## How to run uglifyjs in local

1. Move to `/src/resources/assets`
2. Execute this command
   ```
   ./node_modules/.bin/uglifyjs js/FOO.js -o ../../../js/FOO.min.js
   ```
