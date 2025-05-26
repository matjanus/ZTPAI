import { Link } from "react-router-dom";
import "./QuizListItem.css";

export default function QuizListItem({ quiz }) {
  return (
    <li className="quiz-list-item">
      <Link to={`/quiz/${quiz.id}`} className="quiz-link">{quiz.name}</Link>
    </li>
  );
}