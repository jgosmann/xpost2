<?php

class FromWpApiSaltFetcher implements WpSaltGenerator {

    const FETCH_URL = 'https://api.wordpress.org/secret-key/1.1/salt/';

    public function getSaltDefines() {
        return file_get_contents(self::FETCH_URL);
    }
}

?>
