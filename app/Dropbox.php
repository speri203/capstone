<?php namespace NGAFID;

use GuzzleHttp\Client;

class Dropbox {

    public function api() {
        return new Client([
            'base_url' => 'https://api.dropboxapi.com',
        ]);
    }

}
