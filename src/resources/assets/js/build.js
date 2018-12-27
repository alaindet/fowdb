#!/usr/bin/env node

const app = {
  dependencies: {
    fs: require('fs'),
    path: require('path'),
    uglifyjs: require('uglify-js'),
    yargs: require('yargs')
  },
  args: {},
  config: {},
  dirs: {},
  code: {},
  directorySeparator: '/'
};

// Read CLI arguments
app.args = app.dependencies.yargs.argv;

// ERROR: Missing --page argument
if (!app.args.page) return console.log('ERROR: Missing --page argument');

// Define dir separator (\ for Windows, / for all else), dirs and files
app.dirSeparator = app.args.windows ? '\\' : '/';
app.dirs = defineDirectoriesAndFiles(__dirname, app.args.page);

// Load *.build.json file for this script
try {
  app.config = loadBuildFile(app.dirs.build, app.dependencies.fs);
}

// ERROR: This page does not exist
catch (error) {
  return console.log(`ERROR: The page "${app.args.page}" does not exist!`);
}

// Load all files in sequence and glue them together in a single string
app.code.toMinify = (app.config.dependencies || [])
  .map(file => `${app.dirs.dev}/dependencies/${file}.js`)
  .concat([app.dirs.script])
  .map(file => validatePath(file))
  .map(file => app.dependencies.fs.readFileSync(file, 'utf-8'))
  .join('');

// Minify the code
app.code.minified = app.dependencies.uglifyjs.minify(
  app.code.toMinify,
  {
    warnings: true,
    compress: {
      keep_fargs: false,
      passes: 1 // Try 2
    },
    mangle: {
      toplevel: true // Mangles top-level function names
    }
  }
).code;

// Define output file, create directories if needed then store
saveOutput(
  app.code.minified,
  validatePath(`${app.dirs.prod}/${app.config.output}.min.js`),
  app.dependencies.path,
  app.dependencies.fs
);

function saveOutput(output, outputFile, _path, _fs) {
  buildPath(outputFile, _path, _fs);
  _fs.writeFile(outputFile, output, (error) => {
    console.log(error || `File saved:\n${outputFile}`);
  });
}

// FUNCTIONS ------------------------------------------------------------------

/**
 * Sanitizes filenames to all Windows (\) or all Unix (/) directory separators
 * 
 * @param string filename Filename to check
 * @param string (optional) Separator to use
 * @return string The sanitized filename
 */
function validatePath(filename, sep) {
  const yes = (typeof sep !== 'undefined') ? sep : app.dirSeparator;
  const no = new RegExp((yes === '\\' ? '/' : '\\\\'), 'g');
  return filename.replace(no, yes);
}

/**
 * Defines directories to be used by the builder script
 * 
 * @param string current Current folder (builder.js folder)
 * @return object The directories object
 */
function defineDirectoriesAndFiles(dev, page) {

  dev = validatePath(dev, '/');
  const prod = dev.replace('/src/resources/assets/js', '/js');

  return {
    dev: validatePath(dev),
    prod: validatePath(prod),
    build: validatePath(`${dev}/pages/${page}.build.json`),
    script: validatePath(`${dev}/pages/${page}.js`)
  };

}

/**
 * Loads the *.build.json configuration file and returns it parsed as JSON
 * 
 * @param string page The selected page script to build
 * @param objcet fs Filesystem dependency
 */
function loadBuildFile(filename, fs) {
  return JSON.parse(fs.readFileSync(filename, 'utf8'));
}

/**
 * Checks given directory exists, creates the full path otherwise (recursion)
 * 
 * @param string filename 
 */
function buildPath(filename, path, fs) {
  const dirName = path.dirname(filename);
  if (fs.existsSync(dirName)) return true;
  buildPath(dirName, path, fs);
  fs.mkdirSync(dirName);
}
