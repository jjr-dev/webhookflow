<?php
    namespace App\Services;

    use Luna\Utils\Service;

    use App\Models\RequestMethod as RequestMethodModel;

    use Exception;

    class RequestMethod extends Service {
        public static function findByName($name) {
            try {
                $method = RequestMethodModel::where('name', $name)->first();
                return $method;
            } catch(Exception $e) {
                parent::exception($e);
            }
        }
    }