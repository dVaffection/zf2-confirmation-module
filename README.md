zf2-confirmation-module
=======================

The main purpose of Confirmation module is to unify work with confirmations of different types, so to speak this module provides an abstraction layer upon confirmation mechanism.

---

E.g.
If you want a user to reset his password you need to go through 2 steps: 

1. Send a email to the user's email address with a hash in the link (we use hash to avoid possibility smn can guess this link).
2. After the user followed the link (thus we ensure our user is an email box owner) ask him to enter desired password and its confirmation.


Another example: when a user creates a post specifying his email address we need to ensure he publishes it on behalf of him (he is specified email address owner).

1. Send a email to the user's email address with some hash in the link.
2. After the user followed the link complete the post creation.


So these are good examples of what I call confirmations.
As you may noticed all these confirmations include common parts as hash generation so attaching some callback that has to be triggered on user following the link with this hash. 

---

Let's cut to the chase. 

```php
/* @var $cm \Confirmation\ConfirmationManager\ConfirmationManager */
$cm = $this->getServiceLocator()->get('Confirmation\ConfirmationManager\ConfirmationManager');
$confirmationId = $cm->create('Geo\Controller\CitiesController', 'index', array(1,2,3));
```

Here we obtain an instance of Confirmation manager from Service locator and create a confirmation itself. 
First argument is a controller name and second is an action name that have to be triggered on user's request. 
Third argument is an array of parameters that will be passed to the called controller-action. 
Method returns the confirmation ID (32 characters hash) you are going to use for URL creation.  

```php
// http://rentcolumn.dev/confirmation/2164f1e84bb5c4a215b43f611ef56a00/
$this->url()->fromRoute('index/confirmation', array('id' => $confirmationId));
```

You can obtain confirmation parameters in your controller-action by calling params plugin
```php
// array(1,2,3)
$params = $this->params('confirmation_params');
```

That's it!
