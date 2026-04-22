<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ActiveCampaign API URL
    |--------------------------------------------------------------------------
    |
    | The base URL for your ActiveCampaign account.
    | Example: https://youraccountname.api-us1.com
    |
    */
    'url' => env('ACTIVECAMPAIGN_URL'),

    /*
    |--------------------------------------------------------------------------
    | ActiveCampaign API Key
    |--------------------------------------------------------------------------
    |
    | Your ActiveCampaign API key, found under Settings > Developer.
    |
    */
    'key' => env('ACTIVECAMPAIGN_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Lists
    |--------------------------------------------------------------------------
    |
    | Map your ActiveCampaign list IDs to application-level slugs.
    |
    | Example:
    | [
    |     ['slug' => 'newsletter', 'id' => 1],
    |     ['slug' => 'affiliates',  'id' => 2],
    | ]
    |
    */
    'lists' => [
        // ['slug' => 'newsletter', 'id' => 1],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tags
    |--------------------------------------------------------------------------
    |
    | Map your ActiveCampaign tag IDs and names to application-level slugs.
    |
    | Example:
    | [
    |     ['slug' => 'new-lead',  'name' => 'New Lead',  'id' => 10],
    |     ['slug' => 'converted', 'name' => 'Converted', 'id' => 11],
    | ]
    |
    */
    'tags' => [
        // ['slug' => 'new-lead', 'name' => 'New Lead', 'id' => 10],
    ],

];
