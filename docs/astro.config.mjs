import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';
import starlightThemeFlexoki from 'starlight-theme-flexoki';

export default defineConfig({
    site: 'https://numerik.slashlab.pl',
    integrations: [
        starlight({
            title: 'Numerik',
            description: 'Modern PHP 8.2+ library for validating and parsing Polish identification numbers (PESEL, NIP, REGON, KRS).',
            favicon: '/favicon.png',
            logo: {
                alt: 'Numerik',
                src: './src/assets/logo.svg',
                replacesTitle: true,
            },
            social: [
                { icon: 'github', label: 'GitHub', href: 'https://github.com/sqrcz/numerik' },
            ],
            editLink: {
                baseUrl: 'https://github.com/sqrcz/numerik/edit/main/docs/',
            },
            sidebar: [
                {
                    label: 'Getting Started',
                    items: [
                        { label: 'Introduction', slug: 'index' },
                        { label: 'Installation & Quick Start', slug: 'getting-started' },
                    ],
                },
                {
                    label: 'Identifiers',
                    items: [
                        { label: 'PESEL', slug: 'identifiers/pesel' },
                        { label: 'NIP', slug: 'identifiers/nip' },
                        { label: 'REGON', slug: 'identifiers/regon' },
                        { label: 'KRS', slug: 'identifiers/krs' },
                    ],
                },
                {
                    label: 'Guide',
                    items: [
                        { label: 'Validation Results', slug: 'guide/validation-results' },
                        { label: 'Error Handling', slug: 'guide/error-handling' },
                        { label: 'Algorithms', slug: 'guide/algorithms' },
                    ],
                },
                { label: 'Contributing', slug: 'contributing' },
            ],
            head: [
                {
                    tag: 'meta',
                    attrs: { property: 'og:image', content: 'https://numerik.slashlab.pl/og.png' },
                },
            ],
            lastUpdated: true,
            pagination: true,
            plugins: [starlightThemeFlexoki(
                { accentColor: "cyan" }
            )],
        }),
    ],
});
