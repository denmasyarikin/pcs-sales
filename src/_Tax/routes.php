<?php

$router->get('/', ['as' => 'sales.tax.list', 'uses' => 'TaxController@getList']);
