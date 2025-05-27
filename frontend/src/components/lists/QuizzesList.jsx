import { Link } from 'react-router-dom';
import './QuizzesList.css';



export default function QuizzesList() {
  return (
    <div className="quizzes">
      <div className="quiz-list">
        <h3>{title}</h3>
        {quizzes.map((quiz, index) => (
          <div
            className={`quiz-item ${index % 2 === 0 ? 'even' : 'odd'}`}
            key={quiz.id}
          >
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