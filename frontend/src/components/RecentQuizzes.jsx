import { Link } from 'react-router-dom';
import './RecentQuizzes.css';

const mockQuizzes = [
  { id: 1, title: 'Angielski A1', author: { id: 5, username: 'janek' } },
  { id: 2, title: 'Hiszpański Podstawy', author: { id: 3, username: 'ola' } },
];

export default function RecentQuizzes() {
  return (
    <div className="recent-quizzes">
      <h3>Last played</h3>
      <div className="quiz-list">
        {mockQuizzes.map((quiz) => (
          <div className="quiz-item" key={quiz.id}>
            <Link to={`/quiz/${quiz.id}`} className="quiz-title">
              {quiz.title}
            </Link>
            <Link to={`/user/${quiz.author.id}`} className="quiz-author">
              {quiz.author.username}
            </Link>
          </div>
        ))}
      </div>
    </div>
  );
}