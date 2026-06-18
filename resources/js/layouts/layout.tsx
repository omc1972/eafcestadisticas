import React from "react";
import { Link } from "@inertiajs/react";

interface Props {
    children: React.ReactNode;
}

const Layout: React.FC<Props> = ({ children }) => {
    return (
        <div className="min-h-screen flex flex-col">
            {/* Header */}
            <header className="bg-blue-600 text-white p-4 shadow">
                <div className="container mx-auto flex justify-between items-center">
                    <h1 className="text-xl font-bold">Mi App</h1>
                    <nav>
                        <Link href="/" className="px-3 hover:underline">Inicio</Link>
                        <Link href="/plantillas" className="px-3 hover:underline">Plantillas</Link>
                    </nav>
                </div>
            </header>

            {/* Contenido principal */}
            <main className="flex-1 container mx-auto p-6">
                {children}
            </main>

            {/* Footer */}
            <footer className="bg-gray-100 text-center p-4 text-sm text-gray-600 border-t">
                © {new Date().getFullYear()} Mi App. Todos los derechos reservados.
            </footer>
        </div>
    );
};

export default Layout;
