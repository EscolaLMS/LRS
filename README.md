# Learning Record Stores

[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/LRS/)
[![codecov](https://codecov.io/gh/EscolaLMS/LRS/branch/main/graph/badge.svg?token=NRAN4R8AGZ)](https://codecov.io/gh/EscolaLMS/LRS)
[![phpunit](https://github.com/EscolaLMS/LRS/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/LRS/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/lrs)](https://packagist.org/packages/escolalms/lrs)
[![downloads](https://img.shields.io/packagist/v/escolalms/lrs)](https://packagist.org/packages/escolalms/lrs)
[![downloads](https://img.shields.io/packagist/l/escolalms/lrs)](https://packagist.org/packages/escolalms/lrs)
[![Maintainability](https://api.codeclimate.com/v1/badges/701fa6064d932feadc41/maintainability)](https://codeclimate.com/github/EscolaLMS/LRS/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/701fa6064d932feadc41/test_coverage)](https://codeclimate.com/github/EscolaLMS/LRS/test_coverage)

## Install

1. get package from composer `composer require escolalms/lrs`
2. run the seeder `php artisan db:seed --class="EscolaLms\Lrs\Database\Seeders\LrsSeeder"`
3. make sure that Response Headers are not overwritten by any layer, the `/api/cmi5/**` should response with

```
x-experience-api-version: 1.0.3
```

## Testing

1. Download [cmi5-demo](https://github.com/xapijs/cmi5-demo) and run it with static file server - [`npm run serve`](https://www.npmjs.com/package/serve) or [`php -S localhost:8000`](https://www.php.net/manual/en/features.commandline.webserver.php) is good enough
2. Generate [fetch params](http://aicc.github.io/CMI-5_Spec_Current/flows/lms-flow.html) for a course id calling `/api/cmi5/courses/{id}` endpoint
3. Start course from point 1 with generated params, use `url` object example below

```bash
http://localhost:3000/?endpoint=https%3A%2F%2Fapi-stage.escolalms.com%2Ftrax%2Fapi%2Faf743842-8870-445e-9ca9-f4dcbde65efe%2Fxapi%2Fstd&fetch=https%3A%2F%2Fapi-stage.escolalms.com%2Fapi%2Fcmi5%2Ffetch%3Ftoken%3DeyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5NGExY2RiYi1iZTRiLTRlMjktOTRhZi1mYzk5MjI1YTQ2NmMiLCJqdGkiOiI0ZTliNGE0OTAwZWEwYmEyOWM5ODIwNmVkYzg2YWU0MDQ4M2JmZmNiMGNlYTc2OTU5YjkwZTM1ODk0ZTU2Njk2Mzc4MDA1ZWYyOGMwMmRhZSIsImlhdCI6MTY0MzA0NTEyOS4zMjgxNzgsIm5iZiI6MTY0MzA0NTEyOS4zMjgxODYsImV4cCI6MTY3NDU4MTEyOS4zMjA5MjcsInN1YiI6IjIiLCJzY29wZXMiOltdfQ.hQr_XUoEByCvgFH8S94JLmccqxlg-Zh6dPxEflWD3ABKQQcnSum10IEMrjE9_O0HMHArdwbbi8ebJv0f1XrHEgx2nkw8O5cWIbT27OBnaR86gA3yshg0g5BuM693WvWqH_kc2fK9uF9148b0vcvFsCKX3vru6gLv0NT3WhMKIt7vMSyZrBhD2i1WtgyrpiVz81Tua1f2c7Pcxbir8jijr71Y2H-ZszytxglWvXYtGzCVyY0JiiZV50-did8PhCCTGPKlg3wIYdeVTFRozbTRe-9bF660QhavJr6WMi_ymvnL8hK-BqQWEHTbVdCDXYKMM9WkodqAAk6CWcTRXzPgQT4UTvOPu_rxNMTKU-hA6xaZqGjo5esGId2FMJXxtzMp8MRR2oLxjta6fTmmlgtBXMy1s4thIDlbWIZPSLVx95m85vos2R2TxMc_hKq5FoLp_j78TsJc_zXbxphToVDKybwCAvZC0nreyV3dseNd3urtdDtPmXJnDoasSoQw38GVbj4VlxQ1gq8J9DDtOPmJ3St9j4lMDEXpjZ5WKKKnrmdmxUQi-ti1V4oZ1phARh-KeAIIwfHAR5IdCUVmj6wVvErOUMZwgo9QsvmdoxLVFEe2uwmD9W01crpEKboZ9qtG2cmIDB4PzgrUM6lIwCTtquRPlKMHX-l8PRW3hW7P9Us&actor=%7B%22mbox%22%3A%22mailto%3Aadmin%40escola-lms.com%22%2C%22objectType%22%3A%22Agent%22%2C%22name%22%3A%22Admin+A%22%7D&registration=cfddab74-b3af-4262-ba18-21b0c8f8273c&activityId=https%3A%2F%2Fapi-stage.escolalms.com%2Fxapi%2Factivities%2Fcourse%2F37%2Ftopic%2F671
```
