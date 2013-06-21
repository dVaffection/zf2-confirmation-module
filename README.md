zf2-confirmation-module
=======================

The main purpose of Confirmation module is to unify work with confirmations of different types, so to speak this module provides an abstraction layer upon confirmation mechanism.

Installation instructions
-------------------------

1. `git clone git clone https://github.com/dVaffection/zf2-confirmation-module.git`
2. `cd zf2-confirmation-module`
3. `php composer.phar install`
4. `cd tests`
5. `phpunit`

---

E.g.
If you want a user to reset his password you need to go through 2 steps: 

1. Send a email to the user's email address with a hash in the link (we use hash to avoid possibility smn can guess this link).
2. After the user followed the link (thus we ensure our user is an email box owner) ask him to enter desired password and its confirmation.


Another one: when a user creates a post and specify his email address we need to make sure the user publishes his post on his behalf (our user is the specified email address owner).

1. Send an email to the user's email address with some hash in the link.
2. After the user followed the link complete the post creation.


So these are good examples of what I call confirmations.
As you may noticed all these confirmations include common parts: hash generation and callback attach that is triggered upon user's request. 

---

Let's cut to the chase. 

```php
/* @var $service \Confirmation\Service\Confirmation */
$service = $this->getServiceLocator()->get('confirmationService');
$confirmationId = $service->create('Geo\Controller\CitiesController', 'index', [1,2,3]);
```

Here we obtain an instance of the confirmation service from Service Locator and create a confirmation. 
First argument is a controller name and second is an action name that have to be triggered on user's request. 
Third argument is an array of parameters that will be passed to the called controller-action. 
Method returns the confirmation ID (32 characters hash) you are going to use for URL creation.  

```php
// http://example.com/confirmation/2164f1e84bb5c4a215b43f611ef56a00/
$this->url()->fromRoute('index/confirmation', array('id' => $confirmationId));
```

You can obtain the confirmation parameters in your controller-action by calling the Params plugin
```php
// [1,2,3]
$params = $this->params('confirmation_params');
```

That's it!
