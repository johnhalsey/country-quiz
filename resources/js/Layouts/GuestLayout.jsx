import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ title, children }) {
    return (
        <div className="container mx-auto">
            <div className="min-h-screen flex items-center">
                <div className="w-full">

                    {title && (<h1 className="text-3xl text-center">{title}</h1>)}

                    <main>{children}</main>
                </div>
            </div>
        </div>
    );
}
