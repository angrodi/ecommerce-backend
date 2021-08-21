<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('/login', 'AuthController@login');
    $router->post('/registro', 'AuthController@registro');
    
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->post('/refresh', 'AuthController@refresh');

        $router->get('/categorias', 'CategoriaController@find');
        $router->get('/categorias/{id}', 'CategoriaController@findById');
        $router->post('/categorias', 'CategoriaController@create');
        $router->put('/categorias/{id}', 'CategoriaController@update');
        $router->delete('/categorias/{id}', 'CategoriaController@delete');
    
        $router->get('/productos', 'ProductoController@find');
        $router->get('/productos/{id}', 'ProductoController@findById');
        $router->post('/productos', 'ProductoController@create');
        $router->put('/productos/{id}', 'ProductoController@update');
        $router->delete('/productos/{id}', 'ProductoController@delete');
    
        $router->get('/proveedores', 'ProveedorController@find');
        $router->get('/proveedores/{id}', 'ProveedorController@findById');
        $router->post('/proveedores', 'ProveedorController@create');
        $router->put('/proveedores/{id}', 'ProveedorController@update');
        $router->delete('/proveedores/{id}', 'ProveedorController@delete');
    
        $router->get('/compras', 'CompraController@find');
        $router->get('/compras/{id}', 'CompraController@findById');
        $router->post('/compras', 'CompraController@create');
        $router->put('/compras/{id}', 'CompraController@update');
        $router->delete('/compras/{id}', 'CompraController@delete');
    
        $router->get('/roles', 'RolController@find');
        $router->get('/roles/{id}', 'RolController@findById');
        $router->post('/roles', 'RolController@create');
        $router->put('/roles/{id}', 'RolController@update');
        $router->delete('/roles/{id}', 'RolController@delete');
    
        $router->get('/usuarios', 'UsuarioController@find');
        $router->get('/usuarios/{id}', 'UsuarioController@findById');
        $router->put('/usuarios/{id}', 'UsuarioController@update');
        $router->delete('/usuarios/{id}', 'UsuarioController@delete');
    
        $router->get('/pedidos', 'PedidoController@find');
        $router->get('/pedidos/{id}', 'PedidoController@findById');
        $router->post('/pedidos', 'PedidoController@create');
        $router->put('/pedidos/{id}', 'PedidoController@update');
        $router->delete('/pedidos/{id}', 'PedidoController@delete');
    });

});
