import {Head, Link} from '@inertiajs/react';
import GuestLayout from "@/Layouts/GuestLayout.jsx"

export default function Home () {

    return (
        <>
            <GuestLayout
                title="Capital Cities Quiz"
            >
                <Head title="Country Capitals Quiz"/>

                <p className="text-center mt-8">Think you know your capital cities? Take the test now.</p>
                <div className="mt-12">
                    <Link href="quiz"
                          className="px-5 py-3 flex justify-self-center bg-green-400 hover:bg-green-600 hover:shadow rounded"
                    >
                        Start Quiz
                    </Link>
                </div>

            </GuestLayout>
        </>
    );
}
