<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();  // رقم تعريفي لكل رسالة
            $table->unsignedBigInteger('doctor_id');  // رقم تعريفي للدكتور المرسل
            $table->unsignedBigInteger('patient_id');  // رقم تعريفي للمريض المستلم
            $table->text('message');  // نص الرسالة
            $table->string('sender_type');  // نوع المرسل (دكتور أو مريض)
            $table->timestamps();  // تاريخ الإرسال والتعديل

            // عمل العلاقات بين الجداول
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
