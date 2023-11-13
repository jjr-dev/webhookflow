<?php
    namespace App\Controllers\Api;

    use Luna\Utils\Controller;

    use App\Services\Request as RequestService;
    use App\Services\RequestMethod as RequestMethodService;

    use Exception;

    class Request extends Controller {
        public static function find($req, $res) {
            try {
                $id = $req->param('id');

                $request = RequestService::find($id);
                
                return $res->send(200, parent::success($request), 'json');
            } catch(Exception $e) {
                return $res->send($e->getCode(), parent::error($e->getMessage(), $e->getCode()), 'json');
            }
        }

        public static function list($req, $res) {
            try {
                $requests = RequestService::list();
                
                return $res->send(200, parent::success($requests), 'json');
            } catch(Exception $e) {
                return $res->send($e->getCode(), parent::error($e->getMessage(), $e->getCode()), 'json');
            }
        }

        public static function receive($req, $res) {
            try {
                $body = $req->body();
                $querys = $req->query();
                $headers = $req->header();

                $method = RequestMethodService::findByName($req->getHttpMethod());
                
                if(!$method)
                    throw new Exception("Método inválido", 400);

                $request = RequestService::create($method->id);

                if($body)
                    RequestService::saveBody($request->id, $body);

                if($querys)
                    RequestService::saveQuerys($request->id, $querys);

                if($headers)
                    RequestService::saveHeaders($request->id, $headers);

                return $res->send(200, isset($querys['hub_challenge']) ? $querys['hub_challenge'] : "", 'text/html');
            } catch(Exception $e) {
                return $res->send($e->getCode(), parent::error($e->getMessage(), $e->getCode()), 'json');
            }
        }
    }