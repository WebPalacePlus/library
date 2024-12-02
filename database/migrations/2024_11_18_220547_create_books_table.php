<?php

use App\Models\Field;
use App\Models\Reservation;
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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name',256);
            $table->string('author',256);
            $table->string('publisher',64);
            $table->foreignId('field_id')->constrained('fields','id')->onDelete('cascade');
            $table->date('publish_date');
            $table->string('barcode', 17)->unique();
            $table->tinyInteger('amount')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function queues(){
        return $this->hasMany(Queue::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
