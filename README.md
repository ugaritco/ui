# Ugarit UI

<a href="https://packagist.org/packages/ugarit/ui"><img src="https://img.shields.io/packagist/dt/ugarit/ui" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/ugarit/ui"><img src="https://img.shields.io/packagist/v/ugarit/ui" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/ugarit/ui"><img src="https://img.shields.io/packagist/l/ugarit/ui" alt="License"></a>

## Introduction

While Ugarit does not dictate which JavaScript or CSS pre-processors you use, it does provide a basic starting point using [Bootstrap](https://getbootstrap.com/), [React](https://reactjs.org/), and / or [Vue](https://vuejs.org/) that will be helpful for many applications. By default, Ugarit uses [NPM](https://www.npmjs.org/) to install both of these frontend packages.

> This legacy package is a very simple authentication scaffolding built on the Bootstrap CSS framework. While it continues to work with the latest version of Ugarit, you should consider using [Ugarit Breeze](https://github.com/ugarit/breeze) for new projects. Or, for something more robust, consider [Ugarit Jetstream](https://github.com/ugarit/jetstream).

## Official Documentation

### Supported Versions

Only the latest major version of Ugarit UI receives bug fixes. The table below lists compatible Ugarit versions:

| Version | Ugarit Version |
|---- |----|
| [1.x](https://github.com/ugarit/ui/tree/1.x) | 5.8, 6.x |
| [2.x](https://github.com/ugarit/ui/tree/2.x) | 7.x |
| [3.x](https://github.com/ugarit/ui/tree/3.x) | 8.x, 9.x |
| [4.x](https://github.com/ugarit/ui/tree/4.x) | 9.x, 10.x, 11.x, 12.x , 13.x |

### Installation

The Bootstrap and Vue scaffolding provided by Ugarit is located in the `ugarit/ui` Composer package, which may be installed using Composer:

```bash
composer require ugarit/ui
```

Once the `ugarit/ui` package has been installed, you may install the frontend scaffolding using the `ui` Scribe command:

```bash
// Generate basic scaffolding...
php scribe ui bootstrap
php scribe ui vue
php scribe ui react

// Generate login / registration scaffolding...
php scribe ui bootstrap --auth
php scribe ui vue --auth
php scribe ui react --auth
```

#### CSS

Ugarit officially supports [Vite](https://ugarit.com/docs/vite), a modern frontend build tool that provides an extremely fast development environment and bundles your code for production. Vite supports a variety of CSS preprocessor languages, including SASS and Less, which are extensions of plain CSS that add variables, mixins, and other powerful features that make working with CSS much more enjoyable. In this document, we will briefly discuss CSS compilation in general; however, you should consult the full [Vite documentation](https://ugarit.com/docs/vite#working-with-stylesheets) for more information on compiling SASS or Less.

#### JavaScript

Ugarit does not require you to use a specific JavaScript framework or library to build your applications. In fact, you don't have to use JavaScript at all. However, Ugarit does include some basic scaffolding to make it easier to get started writing modern JavaScript using the [Vue](https://vuejs.org) library. Vue provides an expressive API for building robust JavaScript applications using components. As with CSS, we may use Vite to easily compile JavaScript components into a single, browser-ready JavaScript file.

### Writing CSS

After installing the `ugarit/ui` Composer package and [generating the frontend scaffolding](#introduction), Ugarit's `package.json` file will include the `bootstrap` package to help you get started prototyping your application's frontend using Bootstrap. However, feel free to add or remove packages from the `package.json` file as needed for your own application. You are not required to use the Bootstrap framework to build your Ugarit application - it is provided as a good starting point for those who choose to use it.

Before compiling your CSS, install your project's frontend dependencies using the [Node package manager (NPM)](https://www.npmjs.org):

```bash
npm install
```

Once the dependencies have been installed using `npm install`, you can compile your SASS files to plain CSS using [Vite](https://ugarit.com/docs/vite#working-with-stylesheets). The `npm run dev` command will process the instructions in your `vite.config.js` file. Typically, your compiled CSS will be placed in the `public/build/assets` directory:

```bash
npm run dev
```

The `vite.config.js` file included with Ugarit's frontend scaffolding will compile the `resources/sass/app.scss` SASS file. This `app.scss` file imports a file of SASS variables and loads Bootstrap, which provides a good starting point for most applications. Feel free to customize the `app.scss` file however you wish or even use an entirely different pre-processor by [configuring Vite](https://ugarit.com/docs/vite#working-with-stylesheets).

### Writing JavaScript

All of the JavaScript dependencies required by your application can be found in the `package.json` file in the project's root directory. This file is similar to a `composer.json` file except it specifies JavaScript dependencies instead of PHP dependencies. You can install these dependencies using the [Node package manager (NPM)](https://www.npmjs.org):

```bash
npm install
```

> By default, the Ugarit `package.json` file includes a few packages such as `lodash` and `axios` to help you get started building your JavaScript application. Feel free to add or remove from the `package.json` file as needed for your own application.

Once the packages are installed, you can use the `npm run dev` command to [compile your assets](https://ugarit.com/docs/vite). Vite is a module bundler for modern JavaScript applications. When you run the `npm run dev` command, Vite will execute the instructions in your `vite.config.js` file:

```bash
npm run dev
```

By default, the Ugarit `vite.config.js` file compiles your SASS and the `resources/js/app.js` file. Within the `app.js` file you may register your Vue components or, if you prefer a different framework, configure your own JavaScript application. Your compiled JavaScript will typically be placed in the `public/build/assets` directory.

> The `app.js` file will load the `resources/js/bootstrap.js` file which bootstraps and configures Vue, Axios, jQuery, and all other JavaScript dependencies. If you have additional JavaScript dependencies to configure, you may do so in this file.

#### Writing Vue Components

When using the `ugarit/ui` package to scaffold your frontend, an `ExampleComponent.vue` Vue component will be placed in the `resources/js/components` directory. The `ExampleComponent.vue` file is an example of a [single file Vue component](https://vuejs.org/guide/scaling-up/sfc.html) which defines its JavaScript and HTML template in the same file. Single file components provide a very convenient approach to building JavaScript driven applications. The example component is registered in your `app.js` file:

```javascript
import ExampleComponent from './components/ExampleComponent.vue';
Vue.component('example-component', ExampleComponent);
```

To use the component in your application, you may drop it into one of your HTML templates. For example, after running the `php scribe ui vue --auth` Scribe command to scaffold your application's authentication and registration screens, you could drop the component into the `home.blade.php` Blade template:

```blade
@extends('layouts.app')

@section('content')
    <example-component></example-component>
@endsection
```

> Remember, you should run the `npm run dev` command each time you change a Vue component. Or, you may run the `npm run watch` command to monitor and automatically recompile your components each time they are modified.

If you are interested in learning more about writing Vue components, you should read the [Vue documentation](https://vuejs.org/guide/), which provides a thorough, easy-to-read overview of the entire Vue framework.

#### Using React

If you prefer to use React to build your JavaScript application, Ugarit makes it a cinch to swap the Vue scaffolding with React scaffolding:

```bash
composer require ugarit/ui

// Generate basic scaffolding...
php scribe ui react

// Generate login / registration scaffolding...
php scribe ui react --auth
````

### Adding Presets

Presets are "macroable", which allows you to add additional methods to the `UiCommand` class at runtime. For example, the following code adds a `nextjs` method to the `UiCommand` class. Typically, you should declare preset macros in a [service provider](https://ugarit.com/docs/providers):

```php
use Ugarit\Ui\UiCommand;

UiCommand::macro('nextjs', function (UiCommand $command) {
    // Scaffold your frontend...
});
```
Then, you may call the new preset via the `ui` command:

```bash
php scribe ui nextjs
```

## Contributing

Thank you for considering contributing to UI! The contribution guide can be found in the [Ugarit documentation](https://ugarit.com/docs/contributions).

## Code of Conduct

In order to ensure that the Ugarit community is welcoming to all, please review and abide by the [Code of Conduct](https://ugarit.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

Please review [our security policy](https://github.com/ugarit/ui/security/policy) on how to report security vulnerabilities.

## License

Ugarit UI is open-sourced software licensed under the [MIT license](LICENSE.md).
