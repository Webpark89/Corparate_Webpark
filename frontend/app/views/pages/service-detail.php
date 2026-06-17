<?php

declare(strict_types=1);

$service = $service ?? [];
$topic = $topic ?? [];
$topicList = $topicList ?? [];
$serviceTitle = (string) ($service['title'] ?? 'Services');
$topicTitle = (string) ($topic['title'] ?? 'Service Detail');
$topicKicker = (string) ($topic['kicker'] ?? 'Detail');
$topicImage = asset_url('images/service.png');
$summary = (string) ($topic['summary'] ?? '');

// ปรับมารับค่า content จากฐานข้อมูล (รูปแบบ HTML String) แทน body
$content = (string) ($topic['content'] ?? '');

$highlights = !empty($topic['highlights']) && is_array($topic['highlights']) ? $topic['highlights'] : [];
$serviceSlug = (string) ($service['slug'] ?? '');

?>

<section class="bg-slate-950 text-white">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32 relative z-10">
        <a href="<?= e(route_url('/services')) ?>" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white/90 transition hover:bg-white/10">
            <span aria-hidden="true">‹</span>
            Back to Services
        </a>

        <div class="mt-10 max-w-4xl">
            <p class="text-sm font-semibold uppercase tracking-[0.32em] text-cyan-300"><?= e($topicKicker) ?></p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl"><?= e($topicTitle) ?></h1>
            <p class="mt-5 text-base leading-8 text-slate-300 sm:text-lg"><?= e($summary) ?></p>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-16 sm:py-20">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-4 lg:px-6"> 
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <nav class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm" aria-label="Service Topics">
                <p class="px-3 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">Topics</p>
                <div class="mt-2 space-y-2">
                    <?php foreach ($topicList as $t):
                        $isActive = (($t['slug'] ?? '') === ($topic['slug'] ?? ''));
                        $topicPath = '/service/' . rawurlencode($serviceSlug) . '/' . rawurlencode((string) ($t['slug'] ?? ''));
                    ?>
                        <a class="block rounded-2xl px-4 py-3 text-sm font-semibold transition <?= $isActive ? 'bg-sky-600 text-white shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-sky-50 hover:text-sky-700' ?>" href="<?= e(route_url($topicPath)) ?>" <?= $isActive ? 'aria-current="page"' : '' ?>>
                            <?= e($t['title'] ?? '') ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </nav>
        </aside>

        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <header class="border-b border-slate-100 px-6 py-6 sm:px-10">
                <div class="flex flex-wrap items-center gap-3 text-sm font-semibold text-slate-500">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700"><?= e($serviceTitle) ?></span>
                    <span class="rounded-full bg-sky-50 px-3 py-1 text-sky-700"><?= e($topicTitle) ?></span>
                </div>
            </header>

            <div class="px-6 py-8 sm:px-10 sm:py-10">
                <?php if ($topicImage): ?>
                    <figure class="mb-8 overflow-hidden rounded-3xl bg-slate-100 shadow-sm">
                        <img src="<?= e($topicImage) ?>" alt="<?= e($topicTitle) ?>" class="h-full w-full object-cover" loading="lazy">
                    </figure>
                <?php endif; ?>

                <div class="text-base leading-8 text-slate-600 [&>h3]:text-2xl [&>h3]:font-bold [&>h3]:text-slate-900 [&>h3]:mt-8 [&>h3]:mb-4 [&>p]:mb-5">
                    <?= $content ?>
                </div>

                <?php if ($highlights !== []): ?>
                    <section class="mt-10 rounded-3xl bg-slate-50 p-6 sm:p-8">
                        <h2 class="text-lg font-bold text-slate-900">Key Highlights</h2>
                        <ul class="mt-5 space-y-3">
                            <?php foreach ($highlights as $highlight): ?>
                                <li class="flex items-start gap-3 text-slate-600">
                                    <span class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-600/10 text-sky-700">✓</span>
                                    <span><?= e($highlight) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>
            </div>
        </article>
    </div>
</section>
