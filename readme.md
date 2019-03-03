
<p align="center">
    <img src="https://raw.githubusercontent.com/simondubois/photos/master/screenshot.jpg">
</p>
<p align="center">
    One picture a day in your web browser.<br>
</p>
<p align="center">
    Demo available at https://photos-demo.dubandubois.com
</p>

## Status

[![Maintainability](https://api.codeclimate.com/v1/badges/513ff7a461e0bfc40817/maintainability)](https://codeclimate.com/github/simondubois/photos/maintainability)

This application is now considered as stable.
No more features are planned, but feel free to suggest some if you need.
Feature, fix, UX, logo, translation... any help is welcome !

## Features...

### ....for end users

- large daily photo page with navigation per day.
- full page monthly photo grid with navigation per month.
- full page yearly photo grid (one random photo per month) with navigation per year.
- on the daily view, show photos taken every year at the same date.
- slideshow page with random photos.
- keyboard arrows and touch screen swipe navigation.
- responsive design.

### ...for administrator

- database free application.
- cache on client side.
- built on [Lumen 6](https://lumen.laravel.com/docs/5.7) and [Vuejs 2.5](https://vuejs.org/v2/guide/).

## Requirements

- a web server (tested with Apache).
- PHP >= 7.2.
- GD Library >=2.0 or Imagick PHP extension >=6.5.7.
- [composer](https://getcomposer.org/)

## Deployment

1. Download the code to an empty folder:
```bash
git clone git@github.com:simondubois/photos.git /var/www/photos
```
2. Create the configuration file:
```bash
cd /var/www/photos && cp .env.example .env
```
3. In the `.env` file, set `APP_KEY`:
```
APP_KEY=a_random_string_with_many_symbols
```
4. In the `.env` file, set `PHOTOS_ROOT`:
```
PHOTOS_ROOT="/path/to/photos"
```
5. Install the dependencies:
```bash
composer install --optimize-autoloader --no-dev
```
6. Point the web server to `/var/www/photos/public`.

## FAQ

### How to upload photos?

> This feature is not implemented, and there is no plan to implement it in the future. This is a [KISS](https://en.wikipedia.org/wiki/KISS_principle) project focusing on displaying photos, not managing files.
>
> To upload photos, you have several options:
> - (S)FTP or any file manager provided by your hosting.
> - sync a server folder with a local folder.
> - sync a server folder with a file hosting service.
> - sync a server folder with a personal cloud service.
> - fork the project and implement the feature.

### What about performances?

> On download, the application resizes, interlaces and encodes to JPG (quality 50) the photo. The resulting file is cached by the server on disk and by the client for the same duration. The cache duration is the difference between the current datetime and the date of the photo. So a photo which is one week old will be cached for one week.
>
>To clear server cache, run the following command: `php artisan cache:clear`.

### What about authentication?

> Authentication either requires a database or an external service. Also, authentication requires additional features like lost passwords and account activation.
> This is a [KISS](https://en.wikipedia.org/wiki/KISS_principle) project focusing on displaying photos, avoiding any complexity.
>
> If you want authentication, I recommend you to setup [Basic access authentication](https://en.wikipedia.org/wiki/Basic_access_authentication) at the server level.

### How to change the theme?

> You can easily switch to any other [Bootswatch](https://bootswatch.com/) theme:
> 1. Run `npm install` to install all frontend dependencies.
> 2. Open `resources/app.scss`
> 3. Replace the two occurences to `sketchy` with the template namce of your choice.
> 4. Run `npm run dev` to compile the assets with the new theme.
> Voilà!

### Why are the CSS/JS files not optimized?

> There is a [known issue](https://github.com/thomaspark/bootswatch/issues/878) preventing Bootswatch Sketchy theme to compile in production build.
