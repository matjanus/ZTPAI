import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import './QuizzesList.css';


export default function RecentQuizzes() {
  const [quizzes, setQuizzes] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const token = localStorage.getItem('token');

  const loadQuizzes = async () => {
    try {
      const res = await fetch(`http://127.0.0.1:8000/api/user/last-quizzes?page=${page}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      if (!res.ok) throw new Error('Quiz download error');

      const data = await res.json();

      if (data.length < 10) setHasMore(false);

      setQuizzes((prev) => [...prev, ...data]);
    } catch (err) {
      console.error(err);
      setHasMore(false);
    }
  };

  useEffect(() => {
    loadQuizzes();
  }, [page]);

  const handleLoadMore = () => {
    setPage((p) => p + 1);
  };

  return (
    <div className="quizzes">
      <h3>Last played</h3>
      <div className="quiz-list">
        {quizzes.map((quiz, index) => (
          <div
            className={`quiz-item ${index % 2 === 0 ? 'even' : 'odd'}`}
            key={quiz.id}
          >
            <Link to={`/quiz/${quiz.id}`} className="quiz-title">
              {quiz.quizName}
            </Link>
            <Link to={`/user/${quiz.ownerId}`} className="quiz-author">
              {quiz.owner}
            </Link>
          </div>
        ))}
      </div>
      {hasMore && (
          <button className="load-more-button" onClick={handleLoadMore}>
            Show more
          </button>
      )}
    </div>
  );
}