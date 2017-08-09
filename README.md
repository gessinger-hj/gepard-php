# gepard-php
General purpose communication and synchronization layer for distributed applications / Microservices / events, semaphores, locks and messages for JavaScript, Java, Python and PHP

<!-- MarkdownTOC -->

- [Overview](#overview)
- [Install](#install)
- [Usecases](#usecases)
	- [Updating a Customer-record in the Database](#updating-a-customer-record-in-the-database)

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

If you are interested in the python client see [gepard-python on pypi](https://pypi.python.org/pypi/gepard-python)
and [gepard-python on github](https://github.com/gessinger-hj/gepard-python)

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
# Usecases
## Updating a Customer-record in the Database

Suppose there is a database containing customer base-data like for example name, id, enabled,...

The access to the database for example is done with __laravel/eloquent__. The UPDATE is coded with the following code-snippet:

```PHP
$customer_Id = 1 ;
$customer = App\Customer::find($customer_id);

$customer->name = 'New Customer Name';

$customer->save();
```

Interested 3rd parties now are informed by sending an Event:
```PHP
Client::getInstance()->emit('CUSTOMER_CHANGED', ['CUSTOMER_ID' => $customer_id]);
```

Interested parties for example are:

* A Java program which sends an e-mail.
	```Java
	Client.getInstance().on ( new String[] { "CUSTOMER_CHANGED" }, (e) -> {
		Integer customer_id = e.getValue ( "CUSTOMER_ID" ) ;

		*select customer from database with customer_id*
		*use any mail-api to send mail*
	} ) ;
	```

* A JavaScript program which sends an e-mail:
	```JavaScript
	gepard.getClient().on ( 'CUSTOMER_CHANGED', (e) => {
		let customer_id = e.getValue ( 'CUSTOMER_ID' ) ;

		*select customer from database with customer_id*
		*use any mail-api to send mail*
	} ) ;
	```
* A Python program which sends an e-mail:
	```py

	def on_CUSTOMER_CHANGED ( event ):
		customer_id = event.getValue ( 'CUSTOMER_ID' ) ;

		*select customer from database with customer_id*
		*use any mail-api to send mail*

	gepard.Client.getInstance().on ( 'CUSTOMER_CHANGED', on_CUSTOMER_CHANGED ) ;
	```
* A single page web-app or a React-native app
	```js
	gepard.getWebClient().on ( 'CUSTOMER_CHANGED', (e) => {
		let customer_id = e.getValue ( 'CUSTOMER_ID' ) ;

		if ( customer-data are displayed in any part of the page ) {
			*select customer from database with customer_id via a REST call*
			*update appropriate display*
		}
	} ) ;
	
	```


