# How to build?

1. Run
   ```
   npx ng build --aot --buildOptimizer --optimization --outputPath ../../../ng-test/ --baseHref \/ng-test\/ --prod --progress
   ```
2. Open {dist}/index.html and change \<base href="..."\> to
   ```
   <base href="/ng-test/">
   ```
