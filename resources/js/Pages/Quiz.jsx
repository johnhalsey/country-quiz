import {Head, Link} from '@inertiajs/react';
import GuestLayout from "@/Layouts/GuestLayout.jsx"

export default function Quiz () {

    // get quiz question on monuted

    return (
        <>
            <GuestLayout
                title="What is the capital city of...">
                <Head title="Country Capitals Quiz"/>

                <div className="container mx-auto">
                    Question here
                </div>
            </GuestLayout>


        </>
    );
}
