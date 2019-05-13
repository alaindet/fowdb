#!/usr/bin/env node

/**
 * Arguments
 * 
 * - page (string)
 *     Name of the js file relative to this directory, 
 *     without the file extension
 * 
 * - windows (flag)
 *     Flag to tell the builder it's a Windows development environment
 * 
 * - dev (flag)
 *     Flag to avoid minifying the output
 */

// Define app container
const app = {
  args: {},
  build: {},
  paths: {},
  dependencies: [],
  output: {},
  directorySeparator: ''
};

// Load all dependencies
app.dependencies = {
  babel: require('@babel/core'),
  fs: require('fs'),
  path: require('path'),
  uglifyjs: require('uglify-es'),
  yargs: require('yargs')
};

// Read CLI arguments
app.args = app.dependencies.yargs.argv;

// ERROR: Missing --page argument
if (!app.args.page) {
  return console.log('ERROR: Missing --page argument');
}

// Define dir separator (\ for Windows, / for all else), dirs and files
app.directorySeparator = app.args.windows ? '\\' : '/';

// Define all folders
const dev = sanitizePath(__dirname, '/'); // Temporary dir separator: Unix
const prod = dev.replace('/src/frontend/js', '/assets/js');
app.paths = {
  dev: sanitizePath(dev),
  prod: sanitizePath(prod),
  build: sanitizePath(`${dev}/pages/${app.args.page}.build.json`),
  main: sanitizePath(`${dev}/pages/${app.args.page}.js`),
  output: '' // Defined later
};

// Load *.build.json file for this script
try {
  app.build = loadFileAsJson(app.paths.build);
  app.paths.output = sanitizePath(`${prod}/${app.build.output}.min.js`)
}

// ERROR: This page does not exist
catch (error) {
  return console.log(`ERROR: The page "${app.args.page}" does not exist!`);
}

// Build all dependencies as a single string
app.output.raw = (app.build.dependencies || [])
  .map(file => loadFileAsString(`${app.paths.dev}/dependencies/${file}.js`))
  .join('');

// Load page script
let main = loadFileAsString(app.paths.main);

// Further process partial files
if (app.build.partials) {
  app.build.partials.forEach(partial => {
    const partialPlaceholder = `// @partial: ${partial}\n`;
    const partialPath = `${app.paths.dev}/pages/${partial}.js`;
    const partialString = loadFileAsString(partialPath);
    main = main.replace(partialPlaceholder, partialString);
  })
}

// Glue all the code to be later minify
app.output.raw += main;

// Transpile all code with Babel
app.output.raw = app.dependencies.babel.transform(app.output.raw).code;

// --dev flag: do not minify
if (app.args.dev) {
  app.output.minified = app.output.raw;
}

// Minify the raw code
else {
  app.output.minified = app.dependencies.uglifyjs.minify(
    app.output.raw,
    {
      warnings: true,
      compress: {
        keep_fargs: false,
        passes: 1
      },
      mangle: {
        toplevel: true // Mangles top-level function names
      }
    }
  ).code;
}

// Build non-existing directories, if needed
makeDirectories(app.paths.output);

// Save output file
app.dependencies.fs.writeFile(
  app.paths.output,
  app.output.minified,
  error => console.log(error || `File saved:\n${app.paths.output}`)
);

// FUNCTIONS ------------------------------------------------------------------

/**
 * Sanitizes file paths by using Windows or Unix folder separator exclusively
 * Windows => \
 * Unix    => /
 * 
 * @param string filePath File path to sanitize
 * @param string sep (optional) Separator to use
 * @return string The sanitized file path
 */
function sanitizePath(filePath, sep) {
  const _sep = app.directorySeparator;
  const yes = (typeof sep !== 'undefined') ? sep : _sep;
  const no = new RegExp((yes === '\\' ? '/' : '\\\\'), 'g');
  return filePath.replace(no, yes);
}

/**
 * Reads a file and returns it as a string
 * 
 * @param string filePath File to read
 * @return string Read file as string
 */
function loadFileAsString(filePath) {
  const _fs = app.dependencies.fs;
  return _fs.readFileSync(sanitizePath(filePath), 'utf-8');
}

/**
 * Reads a JSON file and returns it parsed as JSON
 * 
 * @param string filePath File to read
 * @return object JSON parsed object
 */
function loadFileAsJson(filePath) {
  return JSON.parse(loadFileAsString(filePath));
}

/**
 * Accepts a file path and builds directories recursively up to that file
 * 
 * @param string filePath
 */
function makeDirectories(filePath) {
  const _fs = app.dependencies.fs;
  const _path = app.dependencies.path;
  const dir = _path.dirname(filePath);
  if (_fs.existsSync(dir)) {
    return true;
  }
  makeDirectories(dir);
  _fs.mkdirSync(dir);
}
