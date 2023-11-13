<?php
    namespace App\Models;

    use Luna\Db\Model;

    class RequestHeader extends Model {
        public $timestamps = false;
        
        public function request() {
            return $this->belongsTo(Request::class);
        }
    }