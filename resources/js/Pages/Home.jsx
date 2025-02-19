import { Head, Link } from '@inertiajs/react';
import PrimaryButton from "@/Components/PrimaryButton.jsx"

export default function Home() {


    return (
        <>
            <Head title="Country Capitals Quiz" />

            <div className="container">
                <PrimaryButton>
                    Start Quiz
                </PrimaryButton>
            </div>

        </>
    );
}
