# laravel-ghubautopull
**Author: Darkzn98 <darkzn98@gmail.com>**

# About
GHubAutoPull is a non-packagist composer package for Laravel and Lumen. This package used for running `git pull` and `composer install`
on configured server using Github Webhook automatically.

# Requirements
- PHP >= 7.3.9
- Github Webhook
- Server SSH Key Saved to GitHub

# Usage
1. Add vcs record to `repositories` index on `composer.json` like code below:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "ssh://git@github.com:DarkzN98/laravel-ghubautopull.git"
    }
  ],
}
```

2. Run `composer install` on the root directory project to install the package. If there's no new package installed, you need to delete
the `vendor` folder and the `composer.lock` file then re-run `composer install`.
3. Add this package to service your app service provider so the route registered.  

For Laravel `config/app.php`:
```php
'providers' = >[
    // Other Providers
    Darkzn\Ghubautopull\GhubAutopullServiceProvider::class,
],
```

For Lumen `bootstrap/app.php`:
```php
$app->register(Darkzn\Ghubautopull\GhubAutopullServiceProvider::class);
```

4. Configure Github Webhook with the webhook url of `https://{YOUR_APP_URL}/hook.json`. Don't forget to set the Webhook Secret!
5. Configure the `.env` file by adding this code
```txt
# Webhook Secret
GHUB_WEBHOOK_SECRET=testwebhooklaravel
# Webhook Branch (Will exec git pull if github ref matched branch deploy variable)
GHUB_BRANCH_DEPLOY=master
```
6. Test the webhook.
