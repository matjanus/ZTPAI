import { useParams } from 'react-router-dom';
import { useEffect, useState } from 'react';
import axios from 'axios';
import Navbar from '../components/Navbar';
import UserInfo from '../components/UserInfo';
import UserQuizList from '../components/lists/QuizzesList';

export default function UserProfilePage() {
  const { id } = useParams();
  const [user, setUser] = useState(null);
  const [quizzes, setQuizzes] = useState([]);
  const [notFound, setNotFound] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    axios.get(`http://127.0.0.1:8000/api/user/${id}`)
      .then((res) => {
        setUser(res.data);
        setNotFound(false);
      })
      .catch((err) => {
        if (err.response?.status === 404) {
          setNotFound(true);
        }
      })
      .finally(() => setLoading(false));

    axios.get(`http://127.0.0.1:8000/api/user/${id}/quizzes`)
      .then((res) => setQuizzes(res.data))
      .catch(console.error);
  }, [id]);

  if (notFound) return <p style={{ padding: '2rem', color: 'red' }}>Użytkownik nie został znaleziony.</p>;

  return (
    <>
      <Navbar />
      <div>
        <UserInfo username={user.username} />
        <UserQuizList quizzes={quizzes} />
      </div>
    </>
  );
}
