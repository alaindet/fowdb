Description:
  Bump one or more timestamps (to bust the cache)

Usage:
  $ php clint config:timestamp <timestamps>

Values:
  timestamp
    Name(s) of the timestamp to update. Possible values are: generic, css, js,
    img. No value defaults to all timestamps

Options:
  No

Examples:
  $ php clint config:timestamp css js generic
  $ php clint config:timestamp img
  $ php clint config:timestamp
