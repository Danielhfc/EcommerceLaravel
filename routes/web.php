<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('/product/{slug}', 'HomeController@single')->name('product.single');
Route::get('/category/{slug}', 'CategoryController@index')->name('category.single');
Route::get('/store/{slug}', 'StoreController@index')->name('store.single');

Route::get('/products', function(){
     $products = \App\Product::all();
     return view('products', compact('products'));
    })->name('products');

Route::get('/stores', function(){
    $lojas = \App\Store::all();
    return view('stores', compact('lojas'));
})->name('stores');

Route::get('/products-store/{ID_STORE}', function($store){
    $store = \App\Store::find($store);
    $products = $store->products()->paginate(10);
    return view('products-store', compact('products'));
})->name('products-store');

Route::prefix('cart')->name('cart.')->group(function(){
        Route::get('/', 'CartController@index')->name('index');
        Route::post('add', 'CartController@add')->name('add');
       
        Route::get('remove/{slug}', 'CartController@remove')->name('remove');
        Route::get('cancel', 'CartController@cancel')->name('cancel');
});

Route::get('/model', function (){

    $product = \App\Product::find(39);

    return $product->categories;

});

Route::prefix('checkout')->name('checkout.')->group(function(){
    Route::get('/', 'CheckoutController@index')->name('index'); 
    Route::get('/proccess', 'CheckoutController@proccess')->name('proccess');
});

Route::group(['middleware' => ['auth', 'access.control.store.admin']], function(){

    Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function(){
        Route::prefix('stores')->name('stores.')->group(function(){
            Route::get('/', 'StoreController@index')->name('index');
            Route::get('/create', 'StoreController@create')->name('create');
            Route::post('/store', 'StoreController@store')->name('store');
            Route::get('/{store}/edit', 'StoreController@edit')->name('edit');
            Route::post('/update/{store}', 'StoreController@update')->name('update'); 
            Route::get('/destroy/{store}', 'StoreController@destroy')->name('destroy');
        });
    
        Route::resource('stores', 'StoreController');
        Route::resource('products','ProductController');
        Route::resource('categories', 'CategoryController');

        Route::post('photos/remove', 'ProductPhotoController@removePhoto')->name('photo.remove');
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');