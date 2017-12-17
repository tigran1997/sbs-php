<?php namespace Gxela\CreditcardNumberGenerator;

use Illuminate\Support\ServiceProvider;

class CreditcardNumberGeneratorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('gxela/creditcard-number-generator');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['credit_card_generator'] = $this->app->share(function() { return new CreditCardGenerator(); });
        $this->app['credit_card_generator.visa'] = $this->app->share(function() { return CreditCardGenerator::get_visa16(); });
        $this->app['credit_card_generator.mastercard'] = $this->app->share(function() { return CreditCardGenerator::get_mastercard(); });
        $this->app['credit_card_generator.amex'] = $this->app->share(function() { return CreditCardGenerator::get_amex(); });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('credit_card_generator','credit_card_generator.visa', 'credit_card_generator.mastercard', 'credit_card_generator.amex');
    }

}