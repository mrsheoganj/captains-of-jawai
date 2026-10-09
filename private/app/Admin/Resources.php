<?php
declare(strict_types=1);

namespace App\Admin;

/**
 * Content types managed by the generic ContentController.
 * Field: name => [label, type, options]
 * Types: text, slug, textarea, rich, lines, number, bool, select, image, datetime, url
 * Options: required, help, choices, placeholder, width ('half'), rows, maxlength, side (render in sidebar)
 */
final class Resources
{
    private const STATUS = ['published' => 'Published', 'draft' => 'Draft'];
    private const POST_STATUS = ['published' => 'Published', 'draft' => 'Draft', 'review' => 'In review', 'archived' => 'Archived'];

    public static function all(): array
    {
        return [
            'safaris' => [
                'label' => 'Safaris', 'singular' => 'Safari', 'icon' => 'compass', 'area' => 'content',
                'table' => 'safaris', 'title' => 'title', 'order' => 'sort_order, id', 'sortable' => true, 'seo' => true,
                'public' => '/safaris/{slug}/', 'image' => 'image_id', 'search' => ['title', 'tagline'],
                'columns' => ['title' => 'Title', 'category' => 'Category', 'is_featured' => 'Featured', 'status' => 'Status'],
                'fields' => [
                    'title' => ['Title', 'text', ['required' => true]],
                    'slug' => ['URL slug', 'slug', ['from' => 'title', 'prefix' => '/safaris/']],
                    'tagline' => ['Tagline', 'text'],
                    'excerpt' => ['Summary (cards & intro)', 'textarea', ['rows' => 3]],
                    'body' => ['Full description', 'rich'],
                    'highlights' => ['Highlights (one per line)', 'lines', ['rows' => 6]],
                    'duration' => ['Duration', 'text', ['width' => 'half', 'placeholder' => '3–3.5 hours']],
                    'timing' => ['Timings', 'text', ['width' => 'half']],
                    'best_season' => ['Best season', 'text', ['width' => 'half']],
                    'group_size' => ['Group size', 'text', ['width' => 'half']],
                    'status' => ['Status', 'select', ['choices' => self::STATUS, 'side' => true]],
                    'category' => ['Category label', 'text', ['side' => true, 'placeholder' => 'Apex Predator']],
                    'image_id' => ['Hero image', 'image', ['side' => true]],
                    'is_featured' => ['Feature on homepage', 'bool', ['side' => true]],
                    'sort_order' => ['Sort order', 'number', ['side' => true]],
                ],
            ],
            'journeys' => [
                'label' => 'Journeys', 'singular' => 'Journey', 'icon' => 'calendar', 'area' => 'content',
                'table' => 'journeys', 'title' => 'title', 'order' => 'sort_order, id', 'sortable' => true, 'seo' => true,
                'public' => '/journeys/{slug}/', 'image' => 'image_id', 'search' => ['title', 'tagline'],
                'columns' => ['title' => 'Title', 'duration_label' => 'Duration', 'status' => 'Status'],
                'fields' => [
                    'title' => ['Title', 'text', ['required' => true]],
                    'slug' => ['URL slug', 'slug', ['from' => 'title', 'prefix' => '/journeys/']],
                    'tagline' => ['Tagline', 'text'],
                    'excerpt' => ['Summary', 'textarea', ['rows' => 3]],
                    'itinerary' => ['Day-by-day itinerary', 'lines', ['rows' => 8, 'help' => 'One day per line: Day 1 | Title | Description']],
                    'inclusions' => ['Typically included (one per line)', 'lines', ['rows' => 5]],
                    'body' => ['Additional notes', 'rich'],
                    'status' => ['Status', 'select', ['choices' => self::STATUS, 'side' => true]],
                    'duration_label' => ['Duration label', 'text', ['side' => true, 'placeholder' => '3 days · 2 nights']],
                    'image_id' => ['Hero image', 'image', ['side' => true]],
                    'is_featured' => ['Feature on homepage', 'bool', ['side' => true]],
                    'sort_order' => ['Sort order', 'number', ['side' => true]],
                ],
            ],
            'posts' => [
                'label' => 'Field Journal', 'singular' => 'Article', 'icon' => 'file', 'area' => 'content',
                'table' => 'posts', 'title' => 'title', 'order' => 'published_at DESC, id DESC', 'seo' => true,
                'public' => '/journal/{slug}/', 'image' => 'image_id', 'search' => ['title', 'excerpt', 'category'],
                'columns' => ['title' => 'Title', 'category' => 'Category', 'published_at' => 'Published', 'status' => 'Status'],
                'fields' => [
                    'title' => ['Title', 'text', ['required' => true]],
                    'slug' => ['URL slug', 'slug', ['from' => 'title', 'prefix' => '/journal/']],
                    'excerpt' => ['Excerpt / standfirst', 'textarea', ['rows' => 3]],
                    'body' => ['Article', 'rich'],
                    'status' => ['Status', 'select', ['choices' => self::POST_STATUS, 'side' => true]],
                    'published_at' => ['Publish date', 'datetime', ['side' => true, 'help' => 'Future dates are scheduled.']],
                    'category' => ['Category', 'text', ['side' => true, 'list' => 'categories']],
                    'author_name' => ['Author', 'text', ['side' => true]],
                    'reading_time' => ['Reading time (min)', 'number', ['side' => true, 'help' => 'Leave 0 to calculate automatically.']],
                    'image_id' => ['Featured image', 'image', ['side' => true]],
                ],
            ],
            'pages' => [
                'label' => 'Pages', 'singular' => 'Page', 'icon' => 'template', 'area' => 'content',
                'table' => 'pages', 'title' => 'title', 'order' => 'slug', 'seo' => true,
                'public' => '/{slug}/', 'image' => 'image_id', 'search' => ['title', 'slug'],
                'columns' => ['title' => 'Title', 'slug' => 'URL', 'status' => 'Status'],
                'fields' => [
                    'title' => ['Title', 'text', ['required' => true]],
                    'slug' => ['URL path', 'slug', ['from' => 'title', 'prefix' => '/', 'help' => 'Use slashes for nesting, e.g. experiences/rabari-culture. The page with path “about” powers /about/.']],
                    'kicker' => ['Kicker (small line above title)', 'text'],
                    'intro' => ['Intro', 'textarea', ['rows' => 3]],
                    'body' => ['Content', 'rich'],
                    'status' => ['Status', 'select', ['choices' => self::STATUS, 'side' => true]],
                    'image_id' => ['Hero image (optional)', 'image', ['side' => true]],
                    'show_cta' => ['Show consultation banner', 'bool', ['side' => true]],
                ],
            ],
            'faqs' => [
                'label' => 'FAQs', 'singular' => 'FAQ', 'icon' => 'message', 'area' => 'content',
                'table' => 'faqs', 'title' => 'question', 'order' => 'sort_order, id', 'sortable' => true,
                'search' => ['question', 'answer'],
                'columns' => ['question' => 'Question', 'category' => 'Category', 'status' => 'Status'],
                'fields' => [
                    'question' => ['Question', 'text', ['required' => true]],
                    'answer' => ['Answer', 'textarea', ['rows' => 6]],
                    'category' => ['Group', 'text', ['side' => true, 'list' => 'categories']],
                    'status' => ['Status', 'select', ['choices' => self::STATUS, 'side' => true]],
                    'sort_order' => ['Sort order', 'number', ['side' => true]],
                ],
            ],
            'team' => [
                'label' => 'Team / Captains', 'singular' => 'Team member', 'icon' => 'users', 'area' => 'content',
                'table' => 'team', 'title' => 'name', 'order' => 'sort_order, id', 'sortable' => true, 'image' => 'image_id',
                'search' => ['name', 'role'],
                'columns' => ['name' => 'Name', 'role' => 'Role', 'status' => 'Status'],
                'empty' => 'Add your naturalists and trackers here. Until someone is added, the homepage shows your brand values instead.',
                'fields' => [
                    'name' => ['Name', 'text', ['required' => true]],
                    'role' => ['Role', 'text', ['placeholder' => 'Head Naturalist']],
                    'bio' => ['Biography', 'textarea', ['rows' => 6]],
                    'status' => ['Status', 'select', ['choices' => self::STATUS, 'side' => true]],
                    'image_id' => ['Portrait', 'image', ['side' => true]],
                    'sort_order' => ['Sort order', 'number', ['side' => true]],
                ],
            ],
            'testimonials' => [
                'label' => 'Testimonials', 'singular' => 'Testimonial', 'icon' => 'star', 'area' => 'content',
                'table' => 'testimonials', 'title' => 'author', 'order' => 'sort_order, id', 'sortable' => true,
                'search' => ['author', 'quote'],
                'columns' => ['author' => 'Guest', 'origin' => 'From', 'status' => 'Status'],
                'empty' => 'Only publish genuine guest words (ideally verifiable on Google or TripAdvisor). The section stays hidden until one is published.',
                'fields' => [
                    'quote' => ['Quote', 'textarea', ['rows' => 5, 'required' => true]],
                    'author' => ['Guest name', 'text', ['required' => true, 'width' => 'half']],
                    'origin' => ['From (city / country)', 'text', ['width' => 'half']],
                    'source' => ['Source', 'text', ['placeholder' => 'Google Reviews', 'side' => true]],
                    'status' => ['Status', 'select', ['choices' => self::STATUS, 'side' => true]],
                    'sort_order' => ['Sort order', 'number', ['side' => true]],
                ],
            ],
            'redirects' => [
                'label' => 'Redirects', 'singular' => 'Redirect', 'icon' => 'link', 'area' => 'seo',
                'table' => 'redirects', 'title' => 'from_path', 'order' => 'id DESC', 'search' => ['from_path', 'to_url'],
                'columns' => ['from_path' => 'From', 'to_url' => 'To', 'code' => 'Type', 'hits' => 'Hits'],
                'empty' => 'Create 301 redirects when you rename a page so search rankings and old links keep working.',
                'fields' => [
                    'from_path' => ['Old path', 'text', ['required' => true, 'placeholder' => '/old-page/', 'help' => 'Path only, starting with /']],
                    'to_url' => ['New URL or path', 'text', ['required' => true, 'placeholder' => '/safaris/leopard-safari/']],
                    'code' => ['Type', 'select', ['choices' => ['301' => '301 — Permanent', '302' => '302 — Temporary'], 'side' => true]],
                ],
            ],
        ];
    }

    public static function get(string $key): ?array
    {
        return self::all()[$key] ?? null;
    }
}
