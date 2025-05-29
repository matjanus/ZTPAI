import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import ErrorMessage from '../components/ErrorMessage';
import QuizDetails from '../components/QuizDetails';
import './QuizPage.css';

export default function QuizPage() {
  const { id } = useParams();
  const [quiz, setQuiz] = useState(null);
  const [error, setError] = useState(null);
  const token = localStorage.getItem('token');

  useEffect(() => {
    const fetchQuiz = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/quiz/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (res.status === 403) {
          setError({ code: 403, message: 'Access denied to this quiz.' });
        } else if (res.status === 404) {
          setError({ code: 404, message: 'Quiz not found.' });
        } else if (!res.ok) {
          throw new Error('Unexpected error');
        } else {
          const data = await res.json();
          setQuiz(data);
        }
      } catch (err) {
        console.error(err);
        setError({ code: 500, message: 'Something went wrong.' });
      }
    };

    fetchQuiz();
  }, [id]);

  return (
    <div className="quiz-page">
      <Navbar />
      {error ? (
        <ErrorMessage code={error.code} message={error.message} />
      ) : (
        quiz && <QuizDetails title={quiz.title} quizId={quiz.id} />
      )}
    </div>
  );
}
