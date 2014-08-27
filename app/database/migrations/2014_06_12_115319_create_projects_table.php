<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	public function up(){
		Schema::create('projects', function(Blueprint $table){
			$table->increments('id');
            $table->integer('user_id')->unsigned()->default(0)->index();
			$table->string('title',128)->nullable();
			$table->string('slug',128)->nullable();
			$table->text('description')->nullable();
			$table->integer('arhive')->unsigned()->default(0)->nullable();
			$table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

        Schema::create('project_pages', function(Blueprint $table){
            $table->increments('id');
            $table->integer('project_id')->unsigned()->default(0)->index();
            $table->string('title')->nullable();
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->integer('progress')->nullable();
            $table->string('maket')->nullable();
            $table->timestamps();
            $table->foreign('site_id')->references('id')->on('projects')->onDelete('cascade');
        });
	}

	public function down(){
		Schema::drop('projects');
		Schema::drop('project_pages');
	}

}
