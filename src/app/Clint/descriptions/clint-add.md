Description:
  Adds a new Clint command.

Usage:
  clint:add <command> [options]

Arguments:
  command  The command name. Accepts only lowercase letters, - and :
    Ex.: clint:add, env:get, list, help

Options:
  --class  The class name associated with the command. Must be PascalCase.
    If not provided, turns command name in PascalCase, adding "Command"
    Ex.: foo => FooCommand
  --desc   The description that will be used for this command
