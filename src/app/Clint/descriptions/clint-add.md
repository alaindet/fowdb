Description:
  Add a new Clint command

Usage:
  $ php clint clint:add <command> [options]

Values:
  command
    The new command name. Accepts only lowercase letters, - and :

Options:
  --class
    The class name associated with the command. Must be PascalCase. If not
    provided, turns given command name in PascalCase, adding "Command", like
    "foo:bar" => "FooBarCommand"
  --desc
    The description that will be used for this command, defaults to "Description
    for (given command)"

Examples
  $ php clint clint:add foo:bar --class=FooBarCommand --desc="Foo Bar..."
  $ php clint clint:add sandwich:make --class=SandwitchMakeCommand
  $ php clint clint:add do:that --desc="Does that..."
  $ php clint clint:add hello:world
