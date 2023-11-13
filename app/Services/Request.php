<?php
    namespace App\Services;

    use Luna\Utils\Service;

    use App\Models\Request as RequestModel;
    use App\Models\RequestQuery as RequestQueryModel;
    use App\Models\RequestHeader as RequestHeaderModel;

    use Exception;

    class Request extends Service {
        public static function list() {
            try {
                return RequestModel::with('method')->orderByDesc('created_at')->get();
            } catch(Exception $e) {
                var_dump($e->getMessage());
                parent::exception($e);
            }
        }

        public static function find($id) {
            try {
                $request = RequestModel::with('method', 'querys', 'headers')->find($id);

                if($request->body)
                    $request->body = json_decode($request->body);

                return $request;
            } catch(Exception $e) {
                parent::exception($e);
            }
        }

        public static function create($method) {
            try {
                $request = new RequestModel();

                $request->request_method_id = $method;
                $request->save();
                
                return $request;
            } catch(Exception $e) {
                parent::exception($e);
            }
        }

        public static function saveBody($id, $body) {
            try {
                $request = RequestModel::find($id);

                if(!$request)
                    return;

                $request->body = json_encode($body);
                $request->save();
                
                return $request;
            } catch(Exception $e) {
                parent::exception($e);
            }
        }

        public static function saveQuerys($id, $querys) {
            try {
                $request = RequestModel::find($id);

                if(!$request)
                    return;

                foreach($querys as $key => $value) {
                    $query = new RequestQueryModel();
                    $query->key = $key;
                    $query->value = $value;
                    $request->querys()->save($query);
                }
                
                return $request;
            } catch(Exception $e) {
                parent::exception($e);
            }
        }

        public static function saveHeaders($id, $headers) {
            try {
                $request = RequestModel::find($id);

                if(!$request)
                    return;

                foreach($headers as $key => $value) {
                    $query = new RequestHeaderModel();
                    $query->key = $key;
                    $query->value = $value;
                    $request->headers()->save($query);
                }
                
                return $request;
            } catch(Exception $e) {
                parent::exception($e);
            }
        }
    }