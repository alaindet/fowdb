Usage:
  $ php clint command [options] <values>

Commands:
  cards:legality    Rebuild "cards.legality_bit" table field
  cards:sort        Rebuild "cards.sorted_id" table field
  clint:add         Add a new Clint command
  config:cache      Cache the configuration files
  config:clear      Clear the configuration file (parse on each request)
  config:timestamp  Bump one or more timestamps (to bust the cache)
  env:get           Display current environment (development or production)
  env:switch        Switch environment variables (to production or development)
  help              Display a specific command description
  list              Display the list of commands (this command)
  lookup:cache      Cache the lookup data (game-specific)
  sitemap:make      Build sitemap.xml dynamically into public root
