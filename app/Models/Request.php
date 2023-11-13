<?php
    namespace App\Models;

    use Luna\Db\Model;

    class Request extends Model {
        public function method() {
            return $this->belongsTo(RequestMethod::class, 'request_method_id');
        }

        public function querys() {
            return $this->hasMany(RequestQuery::class);
        }

        public function headers() {
            return $this->hasMany(RequestHeader::class);
        }
    }