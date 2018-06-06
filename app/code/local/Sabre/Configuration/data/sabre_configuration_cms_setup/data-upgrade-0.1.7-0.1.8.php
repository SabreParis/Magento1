<?php

//=========Couverts==================//


//>>>>>>>>>>model-marron
$homeIdentifier="model-marron";
Mage::getModel('cms/block')->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-marron')
    ->save();


//>>>>>>>>>>Provençal
$homeIdentifier="model-provençal";
Mage::getModel('cms/block')->load($homeIdentifier,'identifier')
    ->setIdentifier('model-cutlery-provençal')
    ->save();
