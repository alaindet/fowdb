# Paths are relative to package.json since this script is called via NPM
rm -rf ./backend/public/assets/
cp -R ./backend/resources/assets/ ./backend/public/assets/

echo "Folder ./backend/resources/assets/ copied into ./backend/public/assets/"
