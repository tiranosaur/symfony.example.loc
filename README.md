# symfony.example.loc

## frontend
```text
composer require symfony/webpack-encore-bundle
npm install
npm run dev
```
#### ts
```text
npm install typescript ts-loader@^9.0.0 --save-dev

 .addEntry('main', './assets/main.ts')
 .enableTypeScriptLoader()
  {{ encore_entry_script_tags('main') }}
```