<?php

use Botble\Base\Supports\Database\Blueprint;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Review;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('jb_reviews', function (Blueprint $table): void {
            $table->after('account_id', function () use ($table): void {
                $table->morphs('reviewable');
                $table->morphs('created_by');
                $table->index(['reviewable_id', 'reviewable_type', 'status']);
            });
        });

        $reviews = Review::query()->get();

        foreach ($reviews as $review) {
            $review->reviewable_id = $review->company_id;
            $review->reviewable_type = Company::class;
            $review->created_by_id = $review->account_id;
            $review->created_by_type = Account::class;
            $review->save();
        }

        try {
            Schema::table('jb_reviews', function (Blueprint $table): void {
                $table->dropUnique('reviews_unique');
                $table->unique(['reviewable_id', 'reviewable_type', 'created_by_id', 'created_by_type'], 'reviews_unique');
                $table->dropIndex(['account_id', 'company_id', 'status', 'created_at']);
                $table->dropColumn(['company_id', 'account_id']);
            });
        } catch (Throwable) {
        }
    }

    public function down(): void
    {
        Review::query()->where('created_by_type', Company::class)->delete();

        Schema::table('jb_reviews', function (Blueprint $table): void {
            $table->foreignId('company_id')->after('id');
            $table->foreignId('account_id')->after('company_id');
        });

        $reviews = Review::query()->get();

        foreach ($reviews as $review) {
            $review->company_id = $review->reviewable_id;
            $review->account_id = $review->created_by_id;
            $review->save();
        }

        Schema::table('jb_reviews', function (Blueprint $table): void {
            $table->dropUnique('reviews_unique');
        });

        Schema::table('jb_reviews', function (Blueprint $table): void {
            $table->dropIndex(['reviewable_id', 'reviewable_type', 'status']);
            $table->dropMorphs('reviewable');
            $table->dropMorphs('created_by');
            $table->unique(['account_id', 'company_id'], 'reviews_unique');
            $table->index(['account_id', 'company_id', 'status', 'created_at']);
        });
    }
};
