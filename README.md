ripple-php
==========

This project is a PHP wrapper to interact with new Ripple gateways.
The aim of this wrapper is to simplify the integration job and provides with a basic  front-end site for registering users and perform transactions.

Methods:

class gateway:

Public Methods:
  * register($email,$ripple_address)
  * recordDeposit($external_account_id, $currency, $amount)
  * trustlines($ripple_address,$currency=NULL,$amount=NULL)
