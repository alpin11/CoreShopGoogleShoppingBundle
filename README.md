# CoreShop Google Shopping
Create your Google Shopping XML Feeds with CoreShop Products

## [Development]  Installation

#### 1. Composer

```json
    "alpin11/coreshop-google-shopping-bundle": "^1.2"
```

#### 2. Activate
Enable the Bundle in Pimcore Extension Manager or via the CLI

```bash
    bin/console pimcore:bundle:enable CoreShopGoogleShoppingBundle
```

#### 3. Setup
Add custom repositories to `app/config/config.yml` f. e.

```yaml
core_shop_google_shopping:
    repositories:
        - id: app.repository.[REPOSITORY_NAME]
          priority: 200
```

#### 4. Dump feed
```bash
    bin/console coreshop:google-shopping:dump-feed --params='{"store": [STORE_ID], "filename": "[FILENAME]", "locale": "[LOCALE]"}'
```

#### 4. Done
The feed will be saved into `web\google-shopping`
