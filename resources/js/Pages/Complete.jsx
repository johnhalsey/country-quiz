import {Head, Link} from '@inertiajs/react';
import GuestLayout from "@/Layouts/GuestLayout.jsx"

export default function Complete () {

    return (
        <>
            <Head title="Quiz Complete 🤙"/>

            <div className="container mx-auto">

            </div>

            <GuestLayout
                title="Quiz Complete 🤙"
            >
                <Head title="Quiz Complete 🤙"/>

                <p className="text-center mt-8">You completed the Capital Cities quiz, nice job!</p>
                <p className="text-center mt-8">Why not give it another go?</p>
                <div className="mt-12">
                    <Link href="/quiz"
                          className="px-5 py-3 flex justify-self-center bg-green-400 hover:bg-green-600 hover:shadow rounded"
                    >
                        Start Quiz
                    </Link>
                </div>

            </GuestLayout>

        </>
    );
}
