# Page scripts

## What is a *page script* on FoWDB

The somewhat legacy definition here of a *page script* is that of a single JavaScript file loaded on a specific URL it exclusively belongs to (the page). For example, the cards search page will have its own `search.min.js` page script, as well as the spoiler page and so on. This provides the functionality needed for that page only.

Since there are not so many dependencies or no dependencies at all (apart from some 3rd-party ones like jQuery) and since JavaScript on FoWDB just enhances the page without building it (for now, I know modern frameworks exist!), I'm sure these *page scripts* will rarely change once deployed and can safely get cached into the browser.

## At a glance

I wanted page scripts to rely on shared functionalities and I *did not* want to just copy-paste the code around all page scripts. I then built a `build.js` script to glue and minify page scripts together, providing flexibility.

There are 2 NPM scripts which use the custom `build.js` which in turn uses UglifyJS3 to glue and minify all needed JS files together.

```
npm run build-js -- --page=foo
npm run watch-build-js -- --page=foo
```

## Conventions
- **dev** directory => `/src/frontend/js/`
- **prod** directory => `/assets/js/`
- All filenames used here, unless specificed, are relative to the **dev** directory. Ex.: `/pages/foo.js` is `/src/frontend/js/pages/foo.js`
- File tree of the **dev** directory is
  ```
  /
    dependencies/
        components/
            ...
        functions/
            ...
        vendor/
            ...
    pages/
        ...
    build.js
    README.md
  ```
  Anything like `...` up here is any arbitrary combination of directories and/or files depending on how you define them
- Dependencies can "export" their functionality by adding methods and properties to a common `window.APP` object which is always defined on any page, or bind handlers to elements in a IIFE (they're compression-friendly with UglifyJS3)
- Dependencies filenames should be in kebab-case, while their definition into the file should be in camelCase. Ex.:
  ```
  // /dependencies/functions/numbers/foo-bar.js

  function numbersFooBar() {
      //
  }
  ```
  Although polluting the global namespace, this ensures `uglifyjs` mangles the function names as well resulting in a smaller final bundle, that's why pure functions should follow the naming convention defined above.

## Usage

1. Create your page script `/pages/foo/bar.js`

2. Create a companion `*.build.json` file (ex.: `/pages/foo/bar.build.json`), following these rules
   1. It must be located into the same folder as `/pages/foo/bar.js`
   2. It can have only three properties
      - `output` required
      - `dependencies` optional
      - `partials` optional
   3. `output` *(string)* filename of the output file (relative to the **prod** directory, without extension)
   4. `dependencies` *(string[])* filepaths of the dependencies to load before the page script (relative to **dev**`/dependencies` directory, without extension, order is important)
   5. `partials` *(string[])* filepaths of the partials to load inside the page script (relative to the **dev** directory, without extension, order is important)
     - Please see **Partials** below

3. Run
   ```
   npm run build-js --page=foo/bar
   ```
   or
   ```
   npm run watch-build-js --page=foo/bar
   ```

4. If you're on Windows, add the `--windows` flag to the call, like

   ```
   npm run build-js -- --page=foo/bar --windows
   ```
   Please mind that, even on Windows, page names always use forward slashes when called from the terminal, like `--page=foo/bar`. The `build.js` script uses forward slashes internally and translates them to backslashes only when passing the `--windows` flag.

5. If you don't want to compress the final bundle, pass the `--dev` flag to the script, like this
   ```
   npm run build-js -- --page=foo/bar --windows --dev
   ```

## Partials

- Page scripts may get quite big and unmanageable, that's why you can use fake imports called *partials*
- A partial is any `*.js` file, you just have to provide its path
- This file is literally copy-pasted as a string to a specific line ("fake import") while building the output file
- The target line is a comment line like `// @partial: $PARTIAL_PATH` where `$PARTIAL_PATH` is the partial's path, relative to **dev**, without the extension
- Example:
  - Comment on page script: `// @partial: pages/public/cards/search/bootstrap`
  - Partial file:  **dev**`/pages/public/cards/search/bootstrap.js`

## Example

- The build file
  ```
  // /src/frontend/js/pages/foo/bar.build.json
  
  {
      "output": "foo/bar",
      "dependencies": [
          "vendor/bootstrap",
          "functions/numbers/parse-integer",
          "components/dropdown-input"
      ],
      "partials": [
          "pages/foo/partials/my-partial"
      ]
  }
  ```

- The page script
  ```
  // /src/frontend/js/pages/foo/bar.js
  
  console.log("This is the foo/bar page");
  // @partial: pages/foo/partials/my-partial
  ```

- The partial script
  ```
  // /src/frontend/js/pages/foo/partials/my-partial.js
  
  console.log("And this is foo/partial/my-partial");
  ```

- Then execute this
  ```
  npm run build-js -- --page=foo/bar --windows --dev
  ```

- The output file
  ```
  // /assets/js/foo/bar.js
  
  console.log("This is the foo/bar page");
  console.log("And this is foo/partial/my-partial");
  ```

## References

- Fix Windows errors with NPM scripts
https://stackoverflow.com/a/25614832/5653974