<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('active_campaign_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('field_id')->unique()->comment('ActiveCampaign field ID');
            $table->string('perstag')->unique()->comment('ActiveCampaign perstag identifier');
            $table->string('title');
            $table->timestamp('cdate')->nullable()->comment('ActiveCampaign created date');
            $table->timestamp('udate')->nullable()->comment('ActiveCampaign updated date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_campaign_custom_fields');
    }
};
