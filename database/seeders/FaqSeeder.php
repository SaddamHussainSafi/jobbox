<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Faq\Models\Faq;
use Botble\Faq\Models\FaqCategory;

class FaqSeeder extends BaseSeeder
{
    public function run(): void
    {
        Faq::query()->truncate();
        FaqCategory::query()->truncate();

        $categories = [
            [
                'name' => 'General',
            ],
            [
                'name' => 'Buying',
            ],
            [
                'name' => 'Payment',
            ],
            [
                'name' => 'Support',
            ],
        ];

        foreach ($categories as $index => $value) {
            $value['order'] = $index;
            FaqCategory::query()->create($value);
        }

        $faqItems = [
            [
                'question' => 'Where does it come from?',
                'answer' => 'If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages.',
                'category_id' => 1,
            ],
            [
                'question' => 'How JobBox Work?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 1,
            ],
            [
                'question' => 'What is your shipping policy?',
                'answer' => 'Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words.',
                'category_id' => 1,
            ],
            [
                'question' => 'Where To Place A FAQ Page',
                'answer' => 'Just as the name suggests, a FAQ page is all about simple questions and answers. Gather common questions your customers have asked from your support team and include them in the FAQ, Use categories to organize questions related to specific topics.',
                'category_id' => 1,
            ],
            [
                'question' => 'Why do we use it?',
                'answer' => 'It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental.',
                'category_id' => 1,
            ],
            [
                'question' => 'Where can I get some?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 1,
            ],
            [
                'question' => 'Where does it come from?',
                'answer' => 'If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages.',
                'category_id' => 2,
            ],
            [
                'question' => 'How JobBox Work?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 2,
            ],
            [
                'question' => 'What is your shipping policy?',
                'answer' => 'Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words.',
                'category_id' => 2,
            ],
            [
                'question' => 'Where To Place A FAQ Page',
                'answer' => 'Just as the name suggests, a FAQ page is all about simple questions and answers. Gather common questions your customers have asked from your support team and include them in the FAQ, Use categories to organize questions related to specific topics.',
                'category_id' => 2,
            ],
            [
                'question' => 'Why do we use it?',
                'answer' => 'It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental.',
                'category_id' => 2,
            ],
            [
                'question' => 'Where can I get some?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 2,
            ],
            [
                'question' => 'Where does it come from?',
                'answer' => 'If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages.',
                'category_id' => 3,
            ],
            [
                'question' => 'How JobBox Work?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 3,
            ],
            [
                'question' => 'What is your shipping policy?',
                'answer' => 'Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words.',
                'category_id' => 3,
            ],
            [
                'question' => 'Where To Place A FAQ Page',
                'answer' => 'Just as the name suggests, a FAQ page is all about simple questions and answers. Gather common questions your customers have asked from your support team and include them in the FAQ, Use categories to organize questions related to specific topics.',
                'category_id' => 3,
            ],
            [
                'question' => 'Why do we use it?',
                'answer' => 'It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental.',
                'category_id' => 3,
            ],
            [
                'question' => 'Where can I get some?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 3,
            ],
            [
                'question' => 'Where does it come from?',
                'answer' => 'If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages.',
                'category_id' => 4,
            ],
            [
                'question' => 'How JobBox Work?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 4,
            ],
            [
                'question' => 'What is your shipping policy?',
                'answer' => 'Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words.',
                'category_id' => 4,
            ],
            [
                'question' => 'Where To Place A FAQ Page',
                'answer' => 'Just as the name suggests, a FAQ page is all about simple questions and answers. Gather common questions your customers have asked from your support team and include them in the FAQ, Use categories to organize questions related to specific topics.',
                'category_id' => 4,
            ],
            [
                'question' => 'Why do we use it?',
                'answer' => 'It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental.',
                'category_id' => 4,
            ],
            [
                'question' => 'Where can I get some?',
                'answer' => 'To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth.',
                'category_id' => 4,
            ],
        ];

        foreach ($faqItems as $value) {
            Faq::query()->create($value);
        }
    }
}
