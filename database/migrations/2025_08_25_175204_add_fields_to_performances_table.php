<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->string('image')->nullable()->after('content');
            $table->string('category')->default('general')->after('image');
            $table->text('description')->nullable()->after('category');
            $table->string('github_url')->nullable()->after('description');
            $table->string('live_url')->nullable()->after('github_url');
            $table->json('tags')->nullable()->after('live_url');
            $table->integer('views')->default(0)->after('tags');
            $table->boolean('featured')->default(false)->after('views');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performances', function (Blueprint $table) {
            $table->dropColumn([
                'image', 'category', 'description', 'github_url', 
                'live_url', 'tags', 'views', 'featured'
            ]);
        });
    }
};
