import {Head, Link} from '@inertiajs/react';
import GuestLayout from "@/Layouts/GuestLayout.jsx"
import axios from 'axios'
import {useEffect, useState} from "react"
import PrimaryButton from "@/Components/PrimaryButton.jsx"

export default function Quiz ({quizId}) {

    const [loading, setLoading] = useState(true);
    const [data, setData] = useState([])
    const [selected, setSelected] = useState(null)

    useEffect(() => {
        getNextQuestion()
    }, []);

    const getNextQuestion = function () {
        setLoading(true)
        setData([])
        setSelected(null)
        axios.get('api/quiz/' + quizId + '/question')
            .then(response => {
                setData(response.data)
                setLoading(false)
            })
            .catch(error => {
                console.log('some error')
                console.log(error.response)
            })
    }

    const setSelectedAnswer = function(value) {
        console.log(value)
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

    // get quiz question on monuted

    return (
        <>
            {loading && <div>Loading</div>}
            {!loading && <GuestLayout
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
                                        {selected && selected == option.capital && <span className="mr-12">
                                            {option.correct && "Correct  ✅"}

                                            {!option.correct && "Not Quite, try again!"}
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
