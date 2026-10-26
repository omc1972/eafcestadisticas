import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import axios from 'axios';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';

// Nombre de cookie/header CSRF propio de esta app: evita que choque con la
// cookie "XSRF-TOKEN" por defecto de otros proyectos Laravel servidos en
// 127.0.0.1 con otro puerto (las cookies no distinguen por puerto).
axios.defaults.xsrfCookieName = 'EAFC-XSRF-TOKEN';
axios.defaults.xsrfHeaderName = 'X-EAFC-XSRF-TOKEN';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.tsx`,
            import.meta.glob('./pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
