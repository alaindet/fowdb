# How to build?

```
cd {FOWDB}/src/frontend/{ANGULAR}

npx ng build --aot --build-optimizer --optimization --output-path ../../../ng-test/ --base-href /ng-test/ --prod --progress
```

If you're running this on Git Bash for Windows, edit the final index.html like

```
<base href="/ng-test/">
```
