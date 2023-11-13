<?php
    require __DIR__ . '/bootstrap.php';

    use Luna\Db\Database;
    Database::boot(true);
    
    use Illuminate\Database\Capsule\Manager as DB;
    use Illuminate\Database\Schema\Blueprint;

    // Disable Foreign Key Checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Drops
    DB::schema()->dropIfExists('requests');
    DB::schema()->dropIfExists('request_methods');
    DB::schema()->dropIfExists('request_queries');
    DB::schema()->dropIfExists('request_headers');

    // Creates
    DB::schema()->create('request_methods', function (Blueprint $table) {
        $table->id();
        $table->text('name');
    });
    
    DB::schema()->create('requests', function (Blueprint $table) {
        $table->id();
        $table->text('ip');
        $table->longText('body')->nullable();
        $table->unsignedBigInteger('request_method_id');
        $table->timestamps();
        $table->foreign('request_method_id')->references('id')->on('request_methods');
    });

    DB::schema()->create('request_queries', function (Blueprint $table) {
        $table->id();
        $table->text('key');
        $table->text('value');
        $table->unsignedBigInteger('request_id');
        $table->foreign('request_id')->references('id')->on('requests');
    });

    DB::schema()->create('request_headers', function (Blueprint $table) {
        $table->id();
        $table->text('key');
        $table->text('value');
        $table->unsignedBigInteger('request_id');
        $table->foreign('request_id')->references('id')->on('requests');
    });

    // Inserts
    DB::table('request_methods')->insert([
        ['name' => "POST"],
        ['name' => "GET"],
        ['name' => "PUT"],
        ['name' => "DELETE"],
    ]);

    DB::table('requests')->insert([
        ['ip' => "123.123.123-12", 'body' => '{"hello":"world", "Test":["hello"]}', 'request_method_id' => "1", 'created_at' => '2023-11-13 13:45'],
        ['ip' => "123.123.123-13", 'body' => NULL, 'request_method_id' => "1", 'created_at' => '2023-11-13 13:43']
    ]);

    DB::table('request_queries')->insert([
        ['request_id' => 2, 'key' => 'hub_mode', 'value' => 'subscribe'],
        ['request_id' => 2, 'key' => 'hub_mode3', 'value' => 'subscribe2']
    ]);

    // Enable Foreign Key Checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');