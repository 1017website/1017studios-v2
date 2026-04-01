<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        DB::table('users')->insertOrIgnore([
            'name'       => 'Admin 1017Studios',
            'email'      => 'admin@1017studios.com',
            'password'   => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Default settings
        $settings = [
            ['key' => 'company_name',     'value' => '1017Studios'],
            ['key' => 'tagline',          'value' => 'We build brands that move people.'],
            ['key' => 'email',            'value' => 'hello@1017studios.com'],
            ['key' => 'whatsapp',         'value' => '6281234567890'],
            ['key' => 'address',          'value' => 'Surabaya, East Java, Indonesia'],
            ['key' => 'stat_projects',    'value' => '150'],
            ['key' => 'stat_clients',     'value' => '80'],
            ['key' => 'stat_years',       'value' => '5'],
            ['key' => 'stat_satisfaction','value' => '98'],
            ['key' => 'instagram',        'value' => ''],
            ['key' => 'linkedin',         'value' => ''],
            ['key' => 'meta_description', 'value' => '1017Studios — Branding & Digital Agency. We craft brand identities, produce videos, and build world-class websites and apps.'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insertOrIgnore(array_merge($setting, [
                'created_at' => now(), 'updated_at' => now(),
            ]));
        }

        // Default services
        $services = [
            [
                'title'             => 'Brand Identity',
                'short_description' => 'We craft compelling visual identities that tell your story — from logo and color system to typography and brand guidelines.',
                'icon_svg'          => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="24" cy="24" r="20"/><path d="M24 4v40M4 24h40"/><path d="M10 10l28 28M38 10L10 38"/></svg>',
                'tags'              => 'Logo Design, Brand Guidelines, Color System, Typography, Visual Identity',
                'order'             => 1,
            ],
            [
                'title'             => 'Video Production',
                'short_description' => 'From brand films to social ads, we produce cinematic video content that captures attention and drives results across every platform.',
                'icon_svg'          => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="10" width="30" height="28" rx="2"/><path d="M32 18l14-8v28l-14-8V18z"/></svg>',
                'tags'              => 'TVC, Social Media Ads, Brand Film, Motion Graphics, Reels',
                'order'             => 2,
            ],
            [
                'title'             => 'Web Development',
                'short_description' => 'We build high-performance, pixel-perfect websites — from company profiles to complex portals — using modern frameworks and best practices.',
                'icon_svg'          => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="6" width="44" height="36" rx="3"/><path d="M2 16h44M10 6v10M18 26l-6 6 6 6M30 26l6 6-6 6"/></svg>',
                'tags'              => 'Laravel, React, Next.js, Company Profile, E-commerce, CMS',
                'order'             => 3,
            ],
            [
                'title'             => 'App Development',
                'short_description' => 'Native and cross-platform mobile applications built for scale — with intuitive UX and robust backend infrastructure.',
                'icon_svg'          => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="12" y="2" width="24" height="44" rx="4"/><circle cx="24" cy="40" r="2"/><path d="M20 8h8"/></svg>',
                'tags'              => 'Flutter, React Native, iOS, Android, REST API',
                'order'             => 4,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insertOrIgnore(array_merge($service, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Sample testimonials
        $testimonials = [
            [
                'name'    => 'Rizky Pratama',
                'role'    => 'CEO',
                'company' => 'Nusa Digital',
                'quote'   => '1017Studios benar-benar mengubah cara kami dilihat oleh pasar. Brand identity yang mereka ciptakan jauh melampaui ekspektasi kami.',
                'order'   => 1,
            ],
            [
                'name'    => 'Sari Dewi',
                'role'    => 'Marketing Director',
                'company' => 'Pangan Group',
                'quote'   => 'Website yang dibangun timnya sangat cepat, modern, dan konversinya meningkat signifikan sejak kami launch. Highly recommended!',
                'order'   => 2,
            ],
            [
                'name'    => 'Budi Santoso',
                'role'    => 'Founder',
                'company' => 'Katalis App',
                'quote'   => 'Dari logo hingga aplikasi mobile, semuanya dikerjakan profesional dengan komunikasi yang sangat baik. Partner terbaik untuk growth kami.',
                'order'   => 3,
            ],
        ];

        foreach ($testimonials as $t) {
            DB::table('testimonials')->insertOrIgnore(array_merge($t, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
