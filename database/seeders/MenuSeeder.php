<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Post;
use Botble\JobBoard\Enums\AccountTypeEnum;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Job;
use Botble\Language\Models\LanguageMeta;
use Botble\Menu\Facades\Menu;
use Botble\Menu\Models\Menu as MenuModel;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Page\Models\Page;
use Illuminate\Support\Arr;

class MenuSeeder extends BaseSeeder
{
    use HasPageSeeder;

    public function run(): void
    {
        $candidate = Account::query()
            ->where('is_public_profile', true)
            ->where('type', AccountTypeEnum::JOB_SEEKER)
            ->inRandomOrder()
            ->first();

        $data = [
            [
                'name' => 'Main menu',
                'slug' => 'main-menu',
                'location' => 'main-menu',
                'items' => [
                    [
                        'title' => 'Home',
                        'url' => '/',
                        'children' => [
                            [
                                'title' => 'Home 1',
                                'reference_id' => $this->getPageId('Homepage 1'),
                                'reference_type' => Page::class,
                                'position' => 1,
                                'icon_font' => 'fi fi-rr-home',
                            ],
                            [
                                'title' => 'Home 2',
                                'reference_id' => $this->getPageId('Homepage 2'),
                                'reference_type' => Page::class,
                                'position' => 2,
                                'icon_font' => 'fi fi-rr-home',
                            ],
                            [
                                'title' => 'Home 3',
                                'reference_id' => $this->getPageId('Homepage 3'),
                                'reference_type' => Page::class,
                                'position' => 3,
                                'icon_font' => 'fi fi-rr-home',
                            ],
                            [
                                'title' => 'Home 4',
                                'reference_id' => $this->getPageId('Homepage 4'),
                                'reference_type' => Page::class,
                                'position' => 4,
                                'icon_font' => 'fi fi-rr-home',
                            ],
                            [
                                'title' => 'Home 5',
                                'reference_id' => $this->getPageId('Homepage 5'),
                                'reference_type' => Page::class,
                                'position' => 5,
                                'icon_font' => 'fi fi-rr-home',
                            ],
                            [
                                'title' => 'Home 6',
                                'reference_id' => $this->getPageId('Homepage 6'),
                                'reference_type' => Page::class,
                                'position' => 6,
                                'icon_font' => 'fi fi-rr-home',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Find a Job',
                        'reference_id' => $this->getPageId('Companies'),
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'Jobs Grid',
                                'url' => '/jobs?layout=grid',
                                'icon_font' => 'fi fi-rr-briefcase',
                            ],
                            [
                                'title' => 'Jobs List',
                                'url' => '/jobs',
                                'icon_font' => 'fi fi-rr-briefcase',
                            ],
                            [
                                'title' => 'Job Details',
                                'url' => Job::query()->first()->url,
                                'icon_font' => 'fi fi-rr-briefcase',
                            ],
                            [
                                'title' => 'Job External',
                                'url' => Job::query()->skip(1)->first()->url,
                                'icon_font' => 'fi fi-rr-briefcase',
                            ],
                            [
                                'title' => 'Job Hide Company',
                                'url' => Job::query()->skip(2)->first()->url,
                                'icon_font' => 'fi fi-rr-briefcase',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Companies',
                        'reference_id' => 8,
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'Companies',
                                'reference_id' => $this->getPageId('Companies'),
                                'reference_type' => Page::class,
                                'icon_font' => 'fi fi-rr-briefcase',
                            ],
                            [
                                'title' => 'Company Details',
                                'url' => Company::query()->first()->url,
                                'icon_font' => 'fi fi-rr-info',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Candidates',
                        'reference_id' => $this->getPageId('Candidates'),
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'Candidates Grid',
                                'reference_id' => $this->getPageId('Candidates'),
                                'reference_type' => Page::class,
                                'icon_font' => 'fi fi-rr-user',
                            ],
                            [
                                'title' => 'Candidate Details',
                                'url' => $candidate->url,
                                'icon_font' => 'fi fi-rr-info',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Pages',
                        'url' => '#',
                        'children' => [
                            [
                                'title' => 'About Us',
                                'reference_id' => $this->getPageId('About us'),
                                'reference_type' => Page::class,
                                'icon_font' => 'fi fi-rr-star',
                            ],
                            [
                                'title' => 'Pricing Plan',
                                'reference_id' => $this->getPageId('Pricing Plan'),
                                'reference_type' => Page::class,
                                'icon_font' => 'fi fi-rr-database',
                            ],
                            [
                                'title' => 'Contact Us',
                                'reference_id' => $this->getPageId('About us'),
                                'reference_type' => Page::class,
                                'icon_font' => 'fi fi-rr-paper-plane',
                            ],
                            [
                                'title' => 'Register',
                                'url' => route('public.account.register'),
                                'icon_font' => 'fi fi-rr-user-add',
                            ],
                            [
                                'title' => 'Sign in',
                                'url' => route('public.account.login'),
                                'icon_font' => 'fi fi-rr-fingerprint',
                            ],
                            [
                                'title' => 'Reset Password',
                                'url' => route('public.account.password.request'),
                                'icon_font' => 'fi fi-rr-settings',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Blog',
                        'reference_id' => $this->getPageId('Blog'),
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'Blog Grid',
                                'reference_id' => $this->getPageId('Blog'),
                                'reference_type' => Page::class,
                                'icon_font' => 'fi fi-rr-edit',
                            ],
                            [
                                'title' => 'Blog Single',
                                'url' => Post::query()->first()->url,
                                'icon_font' => 'fi fi-rr-document-signed',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Resources',
                'slug' => 'resources',
                'items' => [
                    [
                        'title' => 'About Us',
                        'reference_id' => $this->getPageId('About us'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Our Team',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Products',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Contact',
                        'reference_id' => $this->getPageId('Contact'),
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'Community',
                'slug' => 'community',
                'items' => [
                    [
                        'title' => 'Feature',
                        'reference_id' => $this->getPageId('About us'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Pricing',
                        'reference_id' => $this->getPageId('Pricing Plan'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Credit',
                        'url' => '#',
                    ],
                    [
                        'title' => 'FAQ',
                        'reference_id' => $this->getPageId('FAQs'),
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'Quick links',
                'slug' => 'quick-links',
                'items' => [
                    [
                        'title' => 'iOS',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Android',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Microsoft',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Desktop',
                        'url' => '#',
                    ],
                ],
            ],
            [
                'name' => 'More',
                'slug' => 'more',
                'items' => [
                    [
                        'title' => 'Cookie Policy',
                        'reference_id' => $this->getPageId('Cookie Policy'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Terms',
                        'reference_id' => $this->getPageId('Terms'),
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'FAQ',
                        'reference_id' => $this->getPageId('FAQs'),
                        'reference_type' => Page::class,
                    ],
                ],
            ],
        ];

        MenuModel::query()->truncate();
        MenuLocation::query()->truncate();
        MenuNode::query()->truncate();

        foreach ($data as $index => $item) {
            $menu = MenuModel::query()->create(Arr::except($item, ['items', 'location']));

            if (isset($item['location'])) {
                $menuLocation = MenuLocation::query()->create([
                    'menu_id' => $menu->id,
                    'location' => $item['location'],
                ]);

                LanguageMeta::saveMetaData($menuLocation);
            }

            foreach ($item['items'] as $menuNode) {
                $this->createMenuNode($index, $menuNode, $menu->id);
            }

            LanguageMeta::saveMetaData($menu);
        }

        Menu::clearCacheMenuItems();
    }

    protected function createMenuNode(int $index, array $menuNode, int|string $menuId, int|string $parentId = 0): void
    {
        $menuNode['menu_id'] = $menuId;
        $menuNode['parent_id'] = $parentId;

        if (isset($menuNode['url'])) {
            $menuNode['url'] = str_replace(url(''), '', $menuNode['url']);
        }

        if (Arr::has($menuNode, 'children')) {
            $children = $menuNode['children'];
            $menuNode['has_child'] = true;

            unset($menuNode['children']);
        } else {
            $children = [];
            $menuNode['has_child'] = false;
        }

        $createdNode = MenuNode::query()->create($menuNode);

        if ($children) {
            foreach ($children as $child) {
                $this->createMenuNode($index, $child, $menuId, $createdNode->id);
            }
        }
    }
}
