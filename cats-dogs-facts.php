<?php

/*
Plugin Name: Cats Dogs Facts
Description: Plugin displays random facts about cats or dogs in the footer of a website.
Version: 1.0
Author: Igor Chechura
*/

namespace CatsDogsFacts;

class CatsDogsFacts
{
    private $catsFactsApi;    
    private $dogsFactsApi;
    
    private function __construct()
    {
        $this->catsFactsApi = 'https://cat-fact.herokuapp.com/facts';
        $this->dogsFactsApi = 'https://dog-api.kinduff.com/api/facts';

        add_action('wp_footer', [$this, 'renderFact']);
    }
    
    public static function init()
    {
        return new self();
    }
    
    public function renderFact()
    {
        $fact = $this->getRandomFact();
        
        echo '<p class="cats-dogs-fact">' . $fact . '</p>';
    }

    private function getRandomFact()
    {
        $randomApi = mt_rand(0, 1);

        if ($randomApi === 0) {
            return $this->getCatFact();
        } else {
            return $this->getDogFact();
        }
    }

    private function getCatFact()
    {
        $response = wp_remote_get($this->catsFactsApi);

        if (is_wp_error($response)) {
            return '';
        }

        $data = json_decode($response['body'], true);

        if (empty($data)) {
            return '';
        }

        $fact = $data[mt_rand(0, count($data) - 1)]['text'];

        return $fact;
    }
    
    private function getDogFact()
    {
        $response = wp_remote_get($this->dogsFactsApi);

        if (is_wp_error($response)) {
            return '';
        }

        $data = json_decode($response['body'], true);

        if (empty($data)) {
            return '';
        }
        
        $fact = $data['facts'][0];

        return $fact;
    }
}

add_action('plugins_loaded', ['CatsDogsFacts\CatsDogsFacts', 'init']);
