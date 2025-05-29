import { Link } from 'react-router-dom';
import './QuizDetails.css';

export default function QuizDetails({ title, quizId }) {
  return (
    <div className="quiz-details">
      <h2>{title}</h2>
      <Link to={`/quiz/${quizId}/flashcards`} className="quiz-button">
        ➤ Start Flashcards
      </Link>
    </div>
  );
}
