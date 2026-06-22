<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Users
        $alice = User::create([
            'name'     => 'Alice Johnson',
            'email'    => 'alice@example.com',
            'password' => Hash::make('password'),
        ]);

        $bob = User::create([
            'name'     => 'Bob Smith',
            'email'    => 'bob@example.com',
            'password' => Hash::make('password'),
        ]);

        // Tags
        $tags = collect([
            Tag::create(['name' => 'Bug',           'color' => '#dc3545']),
            Tag::create(['name' => 'Feature',        'color' => '#0d6efd']),
            Tag::create(['name' => 'Frontend',       'color' => '#fd7e14']),
            Tag::create(['name' => 'Backend',        'color' => '#6610f2']),
            Tag::create(['name' => 'Security',       'color' => '#842029']),
            Tag::create(['name' => 'Performance',    'color' => '#e6a817']),
            Tag::create(['name' => 'Design',         'color' => '#e83e8c']),
            Tag::create(['name' => 'Testing',        'color' => '#20c997']),
            Tag::create(['name' => 'Urgent',         'color' => '#ff0000']),
            Tag::create(['name' => 'Documentation',  'color' => '#6c757d']),
        ])->keyBy('name');

        // ── Project 1: E-Commerce Platform ──────────────────────────────
        $ecommerce = Project::create([
            'name'        => 'E-Commerce Platform',
            'description' => 'Full-stack e-commerce web application with product catalog, shopping cart, Stripe payment integration, and order management system.',
            'user_id'     => $alice->id,
            'start_date'  => '2026-01-15',
            'deadline'    => '2026-07-30',
        ]);

        $i1 = Issue::create([
            'project_id'  => $ecommerce->id,
            'title'       => 'Payment gateway fails for orders above $500',
            'description' => 'When a customer submits an order with a total exceeding $500, the Stripe API returns a 400 error. Smaller orders process correctly. The issue appears to be in how we format the amount before sending it to the API.',
            'status'      => 'open',
            'priority'    => 'high',
            'due_date'    => '2026-06-28',
        ]);
        $i1->tags()->attach([$tags['Bug']->id, $tags['Backend']->id, $tags['Urgent']->id]);
        $i1->users()->attach([$alice->id, $bob->id]);
        Comment::create(['issue_id' => $i1->id, 'author_name' => 'Alice Johnson', 'body' => 'Reproduced the issue. Orders above $500 consistently fail with "invalid_integer" from the Stripe API. I think we are passing the amount in dollars (49.99) instead of cents (4999) for larger values.']);
        Comment::create(['issue_id' => $i1->id, 'author_name' => 'Bob Smith',    'body' => 'Confirmed. Found it in PaymentService.php — there is a rounding bug when the amount has more than 2 decimal places. The multiplication to cents overflows. I will push a fix today.']);
        Comment::create(['issue_id' => $i1->id, 'author_name' => 'Alice Johnson', 'body' => 'Good catch Bob. Also make sure to add a unit test for edge cases like $499.99 and $500.01 so we do not regress on this.']);

        $i2 = Issue::create([
            'project_id'  => $ecommerce->id,
            'title'       => 'Shopping cart does not persist after page reload',
            'description' => 'Items added to the cart are lost when the user refreshes the page or navigates away and returns. The cart state is only stored in memory (React state) and is never saved to localStorage or the database for guest users.',
            'status'      => 'in_progress',
            'priority'    => 'high',
            'due_date'    => '2026-06-30',
        ]);
        $i2->tags()->attach([$tags['Bug']->id, $tags['Frontend']->id]);
        $i2->users()->attach([$bob->id]);
        Comment::create(['issue_id' => $i2->id, 'author_name' => 'Bob Smith',    'body' => 'I will implement localStorage persistence for guest carts. Authenticated users will have their cart synced to the database via a debounced API call on every change.']);
        Comment::create(['issue_id' => $i2->id, 'author_name' => 'Alice Johnson', 'body' => 'Sounds good. Make sure we merge the localStorage cart with the database cart when a guest logs in mid-session.']);

        $i3 = Issue::create([
            'project_id'  => $ecommerce->id,
            'title'       => 'Implement product search with filters',
            'description' => 'Add a full-text search bar on the product listing page with support for filtering by category, price range, and availability. Search should be debounced and update results without a full page reload.',
            'status'      => 'in_progress',
            'priority'    => 'medium',
            'due_date'    => '2026-07-10',
        ]);
        $i3->tags()->attach([$tags['Feature']->id, $tags['Frontend']->id, $tags['Backend']->id]);
        $i3->users()->attach([$alice->id]);
        Comment::create(['issue_id' => $i3->id, 'author_name' => 'Alice Johnson', 'body' => 'I have set up the Meilisearch index for products. The backend endpoint is ready at GET /api/products/search?q=&category=&min_price=&max_price=. Frontend integration is next.']);
        Comment::create(['issue_id' => $i3->id, 'author_name' => 'Bob Smith',    'body' => 'The search endpoint looks clean. One suggestion — add a ?in_stock=true filter parameter as well, customers asked for it in the last feedback round.']);

        $i4 = Issue::create([
            'project_id'  => $ecommerce->id,
            'title'       => 'Checkout page loads slowly (3–4 seconds)',
            'description' => 'The checkout page has an average load time of 3.8 seconds. Profiling shows 47 separate database queries being executed on page load, mostly due to N+1 issues in the cart and shipping calculator components.',
            'status'      => 'open',
            'priority'    => 'medium',
            'due_date'    => '2026-07-15',
        ]);
        $i4->tags()->attach([$tags['Performance']->id, $tags['Backend']->id]);
        $i4->users()->attach([$bob->id]);
        Comment::create(['issue_id' => $i4->id, 'author_name' => 'Bob Smith',    'body' => 'Ran Laravel Debugbar — 47 queries on checkout load. The ShippingCalculator calls $product->category on every cart item without eager loading. Adding with("items.product.category") to the cart query should fix most of it.']);
        Comment::create(['issue_id' => $i4->id, 'author_name' => 'Alice Johnson', 'body' => 'Also add a Redis cache layer for shipping rates — they only change once a day but are recalculated on every request right now.']);

        $i5 = Issue::create([
            'project_id'  => $ecommerce->id,
            'title'       => 'Add product image upload with automatic resize',
            'description' => 'Product images are currently stored at full resolution. We need to implement an upload pipeline that generates three sizes: thumbnail (150×150), medium (600×600), and full (1200×1200). Use Spatie Media Library.',
            'status'      => 'closed',
            'priority'    => 'low',
            'due_date'    => '2026-06-20',
        ]);
        $i5->tags()->attach([$tags['Feature']->id, $tags['Backend']->id]);
        $i5->users()->attach([$alice->id]);
        Comment::create(['issue_id' => $i5->id, 'author_name' => 'Alice Johnson', 'body' => 'Done. Spatie Media Library is installed and configured with three conversion sizes. Images are processed in a background job via Laravel Queue so uploads do not block the UI.']);
        Comment::create(['issue_id' => $i5->id, 'author_name' => 'Bob Smith',    'body' => 'Tested on staging — looks great. WebP conversion is also working. Closing this one.']);

        // ── Project 2: Mobile App Redesign ──────────────────────────────
        $mobile = Project::create([
            'name'        => 'Mobile App Redesign',
            'description' => 'Complete UI/UX overhaul of the iOS and Android mobile app based on the new design system. Covers all screens, new color palette, typography update, and dark mode support.',
            'user_id'     => $bob->id,
            'start_date'  => '2026-03-01',
            'deadline'    => '2026-08-15',
        ]);

        $i6 = Issue::create([
            'project_id'  => $mobile->id,
            'title'       => 'Profile picture upload crashes on Android',
            'description' => 'Selecting a photo from the gallery on Android 12+ devices causes an immediate crash. The error log shows a FileUriExposedException, meaning we are passing a file:// URI to an intent that requires a content:// URI.',
            'status'      => 'open',
            'priority'    => 'high',
            'due_date'    => '2026-06-27',
        ]);
        $i6->tags()->attach([$tags['Bug']->id, $tags['Urgent']->id]);
        $i6->users()->attach([$bob->id]);
        Comment::create(['issue_id' => $i6->id, 'author_name' => 'Bob Smith',    'body' => 'Root cause confirmed: FileUriExposedException on Android 12+. We need to use FileProvider to expose the URI via content:// instead of file://. This is a known Android security enforcement from API 24+.']);
        Comment::create(['issue_id' => $i6->id, 'author_name' => 'Alice Johnson', 'body' => 'Also check if the same issue exists on the document attachment feature — it uses the same file picker logic.']);

        $i7 = Issue::create([
            'project_id'  => $mobile->id,
            'title'       => 'Implement dark mode support',
            'description' => 'Add system-aware dark mode across all app screens. Colors should switch automatically based on the device system preference. Hard-coded hex colors must be replaced with semantic design tokens.',
            'status'      => 'in_progress',
            'priority'    => 'medium',
            'due_date'    => '2026-07-20',
        ]);
        $i7->tags()->attach([$tags['Feature']->id, $tags['Design']->id, $tags['Frontend']->id]);
        $i7->users()->attach([$alice->id, $bob->id]);
        Comment::create(['issue_id' => $i7->id, 'author_name' => 'Alice Johnson', 'body' => 'I have created the color token system with light/dark variants for all 28 semantic colors in the design system. The Figma file is updated. Starting the React Native implementation now.']);
        Comment::create(['issue_id' => $i7->id, 'author_name' => 'Bob Smith',    'body' => 'Great. I will handle the native modules for reading the system color scheme preference on both iOS and Android so we can pass the correct theme to the JS layer.']);
        Comment::create(['issue_id' => $i7->id, 'author_name' => 'Alice Johnson', 'body' => 'Heads up — the map screen uses a third-party SDK that does not support dark mode natively. We may need to swap tile layers manually based on the active theme.']);

        $i8 = Issue::create([
            'project_id'  => $mobile->id,
            'title'       => 'Navigation drawer does not close on outside tap',
            'description' => 'The side navigation drawer stays open when the user taps outside of it. It only closes when the user explicitly taps the close button. This is a UX issue — standard mobile behavior expects an outside tap to dismiss the drawer.',
            'status'      => 'open',
            'priority'    => 'medium',
            'due_date'    => '2026-07-05',
        ]);
        $i8->tags()->attach([$tags['Bug']->id, $tags['Frontend']->id]);
        $i8->users()->attach([$bob->id]);
        Comment::create(['issue_id' => $i8->id, 'author_name' => 'Bob Smith', 'body' => 'The issue is in DrawerNavigator config — closeOnPress is set to false by mistake. Also, the backdrop overlay is rendered but the onPress handler is missing. Quick fix.']);

        $i9 = Issue::create([
            'project_id'  => $mobile->id,
            'title'       => 'Redesign login and registration screens',
            'description' => 'Update the login and registration screens to match the new brand identity. New design includes a full-screen background image, updated form inputs with floating labels, and a social login section (Google, Apple).',
            'status'      => 'closed',
            'priority'    => 'medium',
            'due_date'    => '2026-05-30',
        ]);
        $i9->tags()->attach([$tags['Design']->id, $tags['Frontend']->id]);
        $i9->users()->attach([$alice->id]);
        Comment::create(['issue_id' => $i9->id, 'author_name' => 'Alice Johnson', 'body' => 'Both screens are done and match the Figma spec. Social login buttons are in place — OAuth integration itself is tracked under a separate issue.']);
        Comment::create(['issue_id' => $i9->id, 'author_name' => 'Bob Smith',    'body' => 'Reviewed on iPhone 14 Pro and Samsung S23. Looks great on both. Approved and merged.']);

        $i10 = Issue::create([
            'project_id'  => $mobile->id,
            'title'       => 'Write UI tests for the onboarding flow',
            'description' => 'The 5-screen onboarding flow has no automated test coverage. We need Detox end-to-end tests covering: screen transitions, skip button behavior, and the final "Get Started" call to action that navigates to registration.',
            'status'      => 'open',
            'priority'    => 'low',
            'due_date'    => '2026-08-01',
        ]);
        $i10->tags()->attach([$tags['Testing']->id]);
        $i10->users()->attach([$alice->id]);
        Comment::create(['issue_id' => $i10->id, 'author_name' => 'Alice Johnson', 'body' => 'I will set up the Detox test suite this sprint. Starting with the happy path — complete all 5 screens and tap Get Started. Edge cases like skipping from screen 2 and screen 4 will follow.']);

        // ── Project 3: API Gateway ───────────────────────────────────────
        $api = Project::create([
            'name'        => 'API Gateway',
            'description' => 'Centralized REST API gateway handling authentication (JWT), rate limiting, request routing, and logging for all internal microservices. Built with Laravel and deployed on AWS.',
            'user_id'     => $alice->id,
            'start_date'  => '2026-02-10',
            'deadline'    => '2026-06-30',
        ]);

        $i11 = Issue::create([
            'project_id'  => $api->id,
            'title'       => 'JWT token remains valid after user logout',
            'description' => 'When a user logs out, their JWT token is not invalidated server-side. An attacker who intercepts the token before logout can continue making authenticated API requests until the token naturally expires (24 hours).',
            'status'      => 'open',
            'priority'    => 'high',
            'due_date'    => '2026-06-25',
        ]);
        $i11->tags()->attach([$tags['Security']->id, $tags['Backend']->id, $tags['Urgent']->id]);
        $i11->users()->attach([$alice->id, $bob->id]);
        Comment::create(['issue_id' => $i11->id, 'author_name' => 'Alice Johnson', 'body' => 'This is a critical security issue. We need a token blacklist. On logout, we store the token JTI (JWT ID) in Redis with a TTL matching the token expiry. The auth middleware checks the blacklist on every request.']);
        Comment::create(['issue_id' => $i11->id, 'author_name' => 'Bob Smith',    'body' => 'Agreed on the Redis blacklist approach. We should also reduce the token TTL from 24 hours to 1 hour and implement refresh tokens to minimize the exposure window.']);
        Comment::create(['issue_id' => $i11->id, 'author_name' => 'Alice Johnson', 'body' => 'Refresh token implementation is tracked in a separate issue. For now let us ship the blacklist fix — it closes the immediate vulnerability. PR is up for review.']);

        $i12 = Issue::create([
            'project_id'  => $api->id,
            'title'       => 'Rate limiter counter does not reset after 60 seconds',
            'description' => 'The rate limiter is configured to allow 60 requests per minute per IP. However, after a client hits the limit and waits 60 seconds, they are still blocked. The TTL key in Redis appears to never expire correctly.',
            'status'      => 'in_progress',
            'priority'    => 'high',
            'due_date'    => '2026-06-26',
        ]);
        $i12->tags()->attach([$tags['Bug']->id, $tags['Backend']->id]);
        $i12->users()->attach([$bob->id]);
        Comment::create(['issue_id' => $i12->id, 'author_name' => 'Bob Smith',    'body' => 'Found the bug. We are using INCR in Redis without a conditional EXPIRE — so the first request sets the key but never sets a TTL. Subsequent requests just increment forever. Fix: use a Lua script to atomically INCR and set EXPIRE only on the first call.']);
        Comment::create(['issue_id' => $i12->id, 'author_name' => 'Alice Johnson', 'body' => 'The Lua script approach is the right call. Also add an X-RateLimit-Remaining and X-RateLimit-Reset header to the response so clients know when to retry.']);

        $i13 = Issue::create([
            'project_id'  => $api->id,
            'title'       => 'Add request and response logging middleware',
            'description' => 'Implement a middleware that logs every incoming request (method, path, headers, body) and its corresponding response (status code, response time) to a structured log. Sensitive fields like Authorization and passwords must be redacted.',
            'status'      => 'closed',
            'priority'    => 'medium',
            'due_date'    => '2026-05-15',
        ]);
        $i13->tags()->attach([$tags['Feature']->id, $tags['Backend']->id, $tags['Documentation']->id]);
        $i13->users()->attach([$alice->id]);
        Comment::create(['issue_id' => $i13->id, 'author_name' => 'Alice Johnson', 'body' => 'Middleware is live. Logs are structured JSON and sent to CloudWatch. Redaction list covers: Authorization, X-Api-Key, password, token, secret. Response time is measured in milliseconds and included in every log entry.']);
        Comment::create(['issue_id' => $i13->id, 'author_name' => 'Bob Smith',    'body' => 'Verified in staging — CloudWatch is receiving the logs correctly. The redaction is working too, I tested with a real token. Merging.']);

        $i14 = Issue::create([
            'project_id'  => $api->id,
            'title'       => 'Write API documentation for all endpoints',
            'description' => 'All 34 API endpoints need full OpenAPI 3.0 documentation including request parameters, body schemas, response examples, and authentication requirements. Use Scribe to auto-generate from code annotations.',
            'status'      => 'in_progress',
            'priority'    => 'medium',
            'due_date'    => '2026-07-01',
        ]);
        $i14->tags()->attach([$tags['Documentation']->id]);
        $i14->users()->attach([$alice->id, $bob->id]);
        Comment::create(['issue_id' => $i14->id, 'author_name' => 'Bob Smith',    'body' => 'Scribe is configured and generating docs from PHPDoc annotations. I have documented the auth endpoints (login, logout, refresh, me) and the user management endpoints. 18 of 34 endpoints done.']);
        Comment::create(['issue_id' => $i14->id, 'author_name' => 'Alice Johnson', 'body' => 'I will take the service routing endpoints (12 remaining). Bob can you handle the webhook endpoints? They are the most complex and you wrote them.']);

        $i15 = Issue::create([
            'project_id'  => $api->id,
            'title'       => 'Optimize slow database queries on /api/users endpoint',
            'description' => 'The GET /api/users endpoint takes 1.2 seconds on average when there are more than 10,000 users. Query profiling shows a full table scan on the email column. The email field is missing a database index.',
            'status'      => 'open',
            'priority'    => 'medium',
            'due_date'    => '2026-07-05',
        ]);
        $i15->tags()->attach([$tags['Performance']->id, $tags['Backend']->id]);
        $i15->users()->attach([$bob->id]);
        Comment::create(['issue_id' => $i15->id, 'author_name' => 'Bob Smith',    'body' => 'EXPLAIN ANALYZE confirms a sequential scan on users.email. Adding a B-tree index will bring this down to milliseconds. I will also add a composite index on (created_at, id) to speed up the paginated list query.']);
        Comment::create(['issue_id' => $i15->id, 'author_name' => 'Alice Johnson', 'body' => 'While you are at it, check the /api/users/{id}/permissions endpoint too — it was flagged in the last APM report as well.']);
    }
}
