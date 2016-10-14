<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('feed', new Route('/', array(
    '_controller' => 'AppBundle:Feed:index',
)));

$collection->add('feed_show', new Route('/{id}/show', array(
    '_controller' => 'AppBundle:Feed:show',
)));

$collection->add('feed_new', new Route('/new', array(
    '_controller' => 'AppBundle:Feed:new',
)));

$collection->add('feed_create', new Route(
    '/create',
    array('_controller' => 'AppBundle:Feed:create'),
    array('_method' => 'post')
));

$collection->add('feed_edit', new Route('/{id}/edit', array(
    '_controller' => 'AppBundle:Feed:edit',
)));

$collection->add('feed_update', new Route(
    '/{id}/update',
    array('_controller' => 'AppBundle:Feed:update'),
    array('_method' => 'post|put')
));

$collection->add('feed_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'AppBundle:Feed:delete'),
    array('_method' => 'post|delete')
));

return $collection;
