<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('teacher_id', 50)->nullable()->unique('teacher_id');
            $table->unsignedBigInteger('role_id')->nullable()->index('FK_users_roles');
            $table->unsignedInteger('company_id')->nullable()->index('FK_users_companies');
            $table->unsignedInteger('package_id')->default(0);
            $table->timestamp('expired_at')->nullable()->comment('subscription expired time');
            $table->boolean('is_verified')->nullable()->default(false);
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->integer('country_id')->nullable()->comment('Country of Origin');
            $table->integer('subject_id')->nullable()->comment('Subject Taught');
            $table->decimal('hourly_rate', 7)->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('date_of_birth', 50)->nullable();
            $table->text('photo')->nullable();
            $table->string('slug')->nullable()->comment('Tutor Slogan');
            $table->text('introduce_yourself')->nullable();
            $table->text('describe_teaching_experience')->nullable()->comment('Describe your teaching experience, certification and methodlogy');
            $table->text('trial_lesson')->nullable()->comment('Motivate students to book a trial lesson with you.');
            $table->text('video_link')->nullable()->comment('Youtube or Vimeo');
            $table->string('timezone', 50)->nullable();
            $table->enum('teaching_certificate', ['Yes', 'No'])->nullable();
            $table->enum('account_type', ['Personal', 'Business'])->nullable()->comment('Tutor Bank Account Type');
            $table->timestamp('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
