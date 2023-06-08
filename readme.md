# SkiHire2U New Booking Portal

## Frameworks, Packages, and Tools

This solutions is built on the Laravel framework.

* [PHP 8.1](https://www.php.net/)
* [Node 18](https://nodejs.org/en/)
* [Laravel](https://laravel.com/) - PHP Framework

## Services used in production

* AWS Elastic Beanstalk
* AWS CodePipeline
* AWS CodeBuild
* AWS SES


## Local Development
* Check out project from GitHub to local development environment
* Copy `.env.example` to `.env` and fill in the missing entries (`APP_KEY` in next step)
* Generate an `APP_KEY` entry using `php artisan key:generate`
* Install PHP packages with `composer install`
* Install JavaScript dependencies with `npm install`
* Compile JavaScript with `npm run dev` or `npm run dev`
* Set up a web host with `public` folder as root
* Open your host in your local server and enjoy!

## Deployment to Production

This app is built and deployed to production automatically using a CI/CD pipeline when changes are committed to the `main` branch.

When changes are committed on the `main` branch, GitHub fires a webhook to Amazon CodePipeline. This webhook starts a pipeline which gets the code from GitHub, runs a build process through CodeBuild, and then deploys to the production environment on Elastic Beanstalk.

The build process in CodeBuild is configured using `buildspec.yml`.

The Elastic Beanstalk environment, including Nginx server configuration is controlled through the `.platform` directory
