import {Head, Link} from '@inertiajs/react';
import GuestLayout from "@/Layouts/GuestLayout.jsx"
import axios from 'axios'
import {useEffect, useState} from "react"

export default function Quiz ({quizId}) {

    const [loading, setLoading] = useState(true);
    const [data, setData] = useState([])

    useEffect(() => {
        axios.get('api/quiz/' + quizId + '/question')
            .then(response => {
                setData(response.data)
                setLoading(false)
            })
            .catch(() => {
                console.log('some error')
            })
    }, []);

    // get quiz question on monuted

    return (
        <>
            {loading && <div>Loading</div>}
            {!loading && <GuestLayout
                title={'What is the capital city of ' + data.country}>
                <Head title="Country Capitals Quiz"/>

                <div className="container mx-auto">
                    {data.options.map((option, index) => (
                        (<div className="" key={index}>
                            <div className="mx-auto sm:px-6 lg:px-8">
                                <div className="py-6 text-gray-900 border-b">
                                    <input type="radio" name="country-option" value={option.correct}/>
                                    <span className="ml-6">{option.capital}</span>
                                </div>
                            </div>
                        </div>)
                    ))}
                </div>

                {/* buttons for next and finish */}
            </GuestLayout>}


        </>
    );
}
