import {Head, Link} from '@inertiajs/react';
import GuestLayout from "@/Layouts/GuestLayout.jsx"
import axios from 'axios'
import {useEffect, useState} from "react"
import PrimaryButton from "@/Components/PrimaryButton.jsx"

export default function Quiz ({quizId}) {

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([])
    const [selected, setSelected] = useState(null)
    const [error, setError] = useState(true)

    useEffect(() => {
        getNextQuestion()
    }, []);

    const getNextQuestion = function () {
        setLoading(true)
        setData([])
        setSelected(null)
        setError(false)
        axios.get('api/quiz/' + quizId + '/question')
            .then(response => {
                // check if we're being redirected
                if (response.data.redirect) {
                    window.location.href = response.data.redirect
                }

                setData(response.data)
            })
            .catch(error => {
                setError(true)
            })
            .finally(() => {
                setLoading(false)
            })
    }

    const setSelectedAnswer = function(value) {
        setSelected(value)
    }

    function selectedOptionIsCorrect() {
        if (!selected) {
            return false
        }

        // match against prop options
        let match = data.options.find(option => option.capital == selected)
        if (!match) {
            return false
        }

        return match.correct
    }

    return (
        <>
            {loading && <GuestLayout>
                <Head title="Country Capitals Quiz"/>
                <div className="text-center">
                    <span className="mx-auto block w-16 h-16 border-4 border-t-green-500 border-r-blue-500 border-b-yellow-500 border-l-red-500 border-dotted animate-spin rounded-full"></span>
                </div>
            </GuestLayout>}

            {!loading && error && <GuestLayout
                title="There was a problem!"
                >
                <Head title="Country Capitals Quiz"/>

                <div className="container mx-auto mt-6">
                    <div className="mx-auto px-6 lg:px-8 text-center">
                        Uh oh! There was a problem!  Please try and get the next question again

                    </div>

                    <div className="px-6 lg:px-8 mt-12 flex justify-center">
                        <PrimaryButton onClick={getNextQuestion}>Next Question</PrimaryButton>
                    </div>
                </div>
            </GuestLayout>}

            {!loading && !error && <GuestLayout
                title={'What is the capital city of ' + data.country}>
                <Head title="Country Capitals Quiz"/>

                <div className="container mx-auto mt-6">
                    {data.options.map((option, index) => (
                        (<div className="" key={index}>
                            <div className="mx-auto px-6 lg:px-8">
                                <div className={'py-6 text-gray-900 border-b flex cursor-pointer ' +
                                    (selectedOptionIsCorrect() && selected == option.capital ? 'bg-green-400 ' : ' ') +
                                    (!selected || !selectedOptionIsCorrect() ? 'hover:bg-gray-100': '')
                                }
                                     onClick={() => setSelectedAnswer(option.capital)}
                                >
                                    <div className="flex justify-between grow">
                                        <span className="ml-6">{option.capital}</span>
                                        {selected && selected == option.capital && <span className="mr-5 md:mr-12">
                                            {option.correct && "Correct  ✅"}

                                            {!option.correct && "Not quite, try again!"}
                                        </span>}
                                    </div>

                                </div>
                            </div>
                        </div>)
                    ))}

                    <div className="px-6 lg:px-8 mt-12 flex justify-between">
                        <PrimaryButton onClick={getNextQuestion}>Next Question</PrimaryButton>
                    </div>
                </div>

                {/* buttons for next and finish */}
            </GuestLayout>}
        </>
    );
}
