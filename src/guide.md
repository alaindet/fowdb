# Conventions and how-tos on FoWDB

## <input> names

- All inputs names must be in kebab-case, not snake_case Ex.: `<input name="card-id">`
- Variables inside views must be named in snake_case to enhance readabiliy
- Everywhere else, variables must be in camelCase

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

1. Move to `{src}/resources/assets`
2. Execute this command
   ```
   ./node_modules/.bin/uglifyjs js/FOO.js -o ../../../js/FOO.min.js -cm
   ```
- `c` flag: compress (use defaults)
- `m` flag: mangle (use defaults)
- Documentation here: https://www.npmjs.com/package/uglify-js

# FoWDB Clint CLI tools

Clint is a set of CLI tools, inspired by Laravel's Artisan. To start
exploring Clint commands, move to {src}/ and run

```
php clint
```

## How to add commands to Clint

1. Run
   ```
   php clint clint:add foo:bar --class=FooBarCommand --desc="A basic description"
   ```
   - If you omit `--class`, a PascalCase lucky guess is performed,
     like foo:bar => FoorBarCommand
   - If you omit `--desc`, a dummy description is used

2. Edit the `run()` method of `FooBarCommand` (or any name was given the new class). Rember to set `$message` and `$title` props inside `run()` instead of returning anything.
   Ex.:
   ```
   public function run(...): void
   {
       $this->title = 'My custom title';
       $this->message = 'My success message';
   }
   ```
   Any Exception extending `App\Cli\Exceptions\ClintException` automatically gets
   logged to the user in a CLI-friendly format

3. Edit the command's help description in `{src}/app/Clint/descriptions/foo-bar.md`

4. If you did not provide a description, edit the generic description also, in `{src}/app/Clint/descriptions/_all.md`
