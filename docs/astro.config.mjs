import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';
import starlightThemeFlexoki from 'starlight-theme-flexoki';
import starlightLlmsTxt from 'starlight-llms-txt';

export default defineConfig({
    site: 'https://numerik.slashlab.pl',
    integrations: [
        starlight({
            title: {
                en: 'Numerik',
                pl: 'Numerik',
            },
            description: 'Modern PHP 8.3+ library for validating and parsing Polish identification numbers (PESEL, NIP, REGON, KRS, NRB, VAT-EU, IBAN).',
            defaultLocale: 'root',
            locales: {
                root: {
                    label: 'English',
                    lang: 'en',
                },
                pl: {
                    label: 'Polski',
                    lang: 'pl',
                },
            },
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
                    translations: { pl: 'Pierwsze kroki' },
                    items: [
                        { label: 'Introduction', slug: 'index', translations: { pl: 'Wprowadzenie' } },
                        { label: 'Installation & Quick Start', slug: 'getting-started', translations: { pl: 'Instalacja i szybki start' } },
                    ],
                },
                {
                    label: 'Identifiers',
                    translations: { pl: 'Identyfikatory' },
                    items: [
                        { label: 'PESEL', slug: 'identifiers/pesel' },
                        { label: 'NIP', slug: 'identifiers/nip' },
                        { label: 'REGON', slug: 'identifiers/regon' },
                        { label: 'KRS', slug: 'identifiers/krs' },
                        { label: 'NRB', slug: 'identifiers/nrb' },
                        { label: 'VAT-EU', slug: 'identifiers/vat-eu' },
                        { label: 'IBAN', slug: 'identifiers/iban' },
                    ],
                },
                {
                    label: 'Guide',
                    translations: { pl: 'Poradnik' },
                    items: [
                        { label: 'Validation Results', slug: 'guide/validation-results', translations: { pl: 'Wyniki walidacji' } },
                        { label: 'Error Handling', slug: 'guide/error-handling', translations: { pl: 'Obsługa błędów' } },
                        { label: 'Algorithms', slug: 'guide/algorithms', translations: { pl: 'Algorytmy' } },
                    ],
                },
                {
                    label: 'Integrations',
                    translations: { pl: 'Integracje' },
                    items: [
                        { label: 'Laravel', slug: 'integrations/laravel', translations: { pl: 'Laravel' } },
                    ],
                },
                { label: 'Contributing', slug: 'contributing', translations: { pl: 'Współtworzenie' } },
            ],
            head: [
                {
                    tag: 'meta',
                    attrs: { property: 'og:image', content: 'https://numerik.slashlab.pl/og.png' },
                },
            ],
            components: {
                Footer: './src/components/Footer.astro',
            },
            lastUpdated: true,
            pagination: true,
            plugins: [
                starlightLlmsTxt(),
                starlightThemeFlexoki({ accentColor: "cyan" }),
            ],
        }),
    ],
});
