import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import './QuizzesList.css';

export default function MyCourseList() {
  const [courses, setCourses] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const token = localStorage.getItem('jwt');
  const navigate = useNavigate();

  useEffect(() => {
    loadCourses();
    // eslint-disable-next-line
  }, [page]);

  const loadCourses = async () => {
    try {
      const res = await fetch(`http://127.0.0.1:8000/api/user/my-quizzes?page=${page}`, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      if (!res.ok) throw new Error('Błąd pobierania kursów');

      const data = await res.json();
      if (data.length < 10) setHasMore(false);
      setCourses((prev) => [...prev, ...data]);
    } catch (err) {
      console.error(err);
      setHasMore(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Czy na pewno chcesz usunąć kurs?')) return;

    try {
      const res = await fetch(`http://127.0.0.1:8000/api/quiz/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });

      if (res.ok) {
        setCourses((prev) => prev.filter((q) => q.id !== id));
      } else {
        alert('Błąd podczas usuwania');
      }
    } catch (err) {
      alert('Błąd połączenia z serwerem');
    }
  };

  return (
    <div className="quizzes">
        <div className="quiz-list">
        {courses.map((quiz, index) => (
            <div className={`quiz-item ${index % 2 === 0 ? 'even' : 'odd'}`} key={quiz.id}>
            <Link to={`/quiz/${quiz.id}`} className="quiz-title">
                {quiz.title}
            </Link>
            <div className="quiz-actions">
                <button onClick={() => navigate(`/quiz/${quiz.id}/edit`)}>Edytuj</button>
                <button onClick={() => handleDelete(quiz.id)}>Usuń</button>
            </div>
        </div>
        ))}
        {hasMore && (
            <button className="load-more-button" onClick={() => setPage((p) => p + 1)}>
            ➕ Załaduj więcej
            </button>
        )}
        </div>
    </div>
  );
}
