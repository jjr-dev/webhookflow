<?php
    namespace App\Controllers\Pages;

    use Luna\Utils\View;
    use Luna\Utils\Controller;
    use Luna\Utils\Component;

    use App\Services\Request as RequestService;

    class Home extends Controller {
        static function homePage($req, $res) {
            $title = 'WebHook - Luna';

            return $res->send(200, parent::page($title, View::render('home')));
        }
    }