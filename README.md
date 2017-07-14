# gepard-php
General purpose communication and synchronization layer for distributed applications / Microservices / events, semaphores, locks and messages for JavaScript, Java, Python and PHP

<!-- MarkdownTOC -->

- [Overview](#overview)
- [Install](#install)

<!-- /MarkdownTOC -->

# Overview
This __PHP__ module implements a simple client for the __GEPARD__ middleware for general purpose distributed applications.

In order to use this __PHP__ client you must have installed the __GEPARD__ middleware.
This is simply done by executing the command:

```bash
	npm install gepard
```

Prerequisite for this command is the installation of node and npm.
For more information see [gepard on npm](https://www.npmjs.com/package/gepard) and [gessinger-hj/gepard on github](https://github.com/gessinger-hj/gepard).

If you are interested in the python client see [](https://pypi.python.org/pypi/gepard-python)

# Install
If you not yet have a composer.json create this file with the following content:
```json
{
    "require": {
        "gepard/gepard-php": ">=1.0"
    },
    "minimum-stability": "dev" 
}
```
If this composer.json file already exists in your project-directory add the body-lines above.
After this is done execute the command:
```bash
composer install
```

